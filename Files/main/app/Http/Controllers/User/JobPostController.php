<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\FileType;
use App\Models\JobCategory;
use App\Models\JobPost;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;

class JobPostController extends Controller
{
    function index() {
        $pageTitle = "Job History";
        $user      = auth()->user();
        $jobs      = $user->jobs()
            ->withCount([
                'applications' => fn($query) => $query->where('author_notified', ManageStatus::NO),
            ])
            ->searchable(['job_code', 'title'])
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.job.history', compact('pageTitle', 'user', 'jobs'));
    }

    function create() {
        $pageTitle = 'Create Job';
        $user      = auth()->user();

        $categories = JobCategory::whereHas('subcategories', function (Builder $builder) {
            $builder->where('status', ManageStatus::ACTIVE);
        })
            ->with(['subcategories' => fn ($query) => $query->active()->orderBy('name')])
            ->active()
            ->orderBy('name')
            ->get();

        $fileTypes = FileType::active()->orderBy('type')->get();

        return view($this->activeTheme . 'user.job.create', compact('pageTitle', 'user', 'categories', 'fileTypes'));
    }

    function store() {
        $this->validateJobData();

        [$category, $subcategory] = $this->ensureValidCategoryAndSubcategory();
        $selectedFileTypes        = $this->ensureValidFileTypes();

        $budget = request('quantity') * request('rate') * request('vacancy');
        $user   = auth()->user();

        try {
            // Job Image
            $image = fileUploader(request()->file('image'), getFilePath('job'), getFileSize('job'));

            // Job Attachment
            $attachment = request()->hasFile('job_attachment')
                ? fileUploader(request()->file('job_attachment'), getFilePath('jobAttachment'))
                : null;

            $originalName = $attachment
                ? request()->file('job_attachment')->getClientOriginalName()
                : null;
        } catch (Exception) {
            $toast[] = ['error', 'File uploading process has failed'];

            return back()->with('toasts', $toast);
        }

        // Job Post
        $jobPost                           = new JobPost();
        $jobPost->user_id                  = $user->id;
        $jobPost->category_id              = $category->id;
        $jobPost->subcategory_id           = $subcategory->id;
        $jobPost->job_code                 = getTrx();
        $jobPost->image                    = $image;
        $jobPost->title                    = request('title');
        $jobPost->description              = request('description');
        $jobPost->quantity                 = request('quantity');
        $jobPost->rate                     = request('rate');
        $jobPost->total_budget             = $budget;
        $jobPost->vacancy                  = request('vacancy');
        $jobPost->has_job_proof            = request('job_proof');
        $jobPost->file_types               = $selectedFileTypes;
        $jobPost->job_attachment           = $attachment;
        $jobPost->attachment_original_name = $originalName;
        $jobPost->save();

        // Admin Notification
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = "$user->fullname has posted a new job.";
        $adminNotification->click_url = urlPath('admin.jobs.pending');
        $adminNotification->save();

        // Notify User
        notify($user, 'JOB_POST', [
            'job_code' => $jobPost->job_code,
            'title'    => $jobPost->title,
            'amount'   => showAmount($jobPost->total_budget),
        ]);

        $toast[] = ['success', 'A new job has been created'];

        return to_route('user.job.history')->with('toasts', $toast);
    }

    function edit(JobPost $job) {
        $response = Gate::inspect('edit', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        if ($job->status == ManageStatus::JOB_REJECTED || $job->status == ManageStatus::JOB_UNAVAILABLE) {
            $toast[] = ['error', 'You cannot edit this job.'];

            return back()->with('toasts', $toast);
        }

        $pageTitle  = 'Edit Job';
        $user       = auth()->user();
        $categories = JobCategory::whereHas('subcategories', function (Builder $builder) {
            $builder->where('status', ManageStatus::ACTIVE);
        })
            ->with(['subcategories' => fn ($query) => $query->active()->orderBy('name')])
            ->active()
            ->orderBy('name')
            ->get();

        $fileTypes = FileType::active()->orderBy('type')->get();

        return view($this->activeTheme . 'user.job.edit', compact('job', 'pageTitle', 'user', 'categories', 'fileTypes'));
    }

    function downloadAttachment(JobPost $job) {
        $response = Gate::inspect('download', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $attachmentPath = getFilePath('jobAttachment') . '/' . $job->job_attachment;

        if (file_exists($attachmentPath)) {
            return response()->download($attachmentPath, $job->attachment_original_name);
        }

        abort(404);
    }

    function update(JobPost $job) {
        $this->validateJobData(true);

        $response = Gate::inspect('update', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        [$category, $subcategory] = $this->ensureValidCategoryAndSubcategory();
        $selectedFileTypes        = $this->ensureValidFileTypes();

        $user           = auth()->user();
        $oldTotalBudget = $job->total_budget;
        $newTotalBudget = null;

        if (request('quantity') != $job->quantity || request('rate') != $job->rate || request('vacancy') != $job->vacancy) {
            $newTotalBudget = request('quantity') * request('rate') * request('vacancy');
        }

        if ($job->status == ManageStatus::JOB_REJECTED || $job->status == ManageStatus::JOB_UNAVAILABLE) {
            $toast[] = ['error', 'You cannot update this job.'];

            return back()->with('toasts', $toast)->withInput();
        }

        try {
            // Job Image
            $image = request()->hasFile('image')
                ? fileUploader(request()->file('image'), getFilePath('job'), getFileSize('job'), $job->image)
                : $job->image;

            // Job Attachment
            $attachment = request()->hasFile('job_attachment')
                ? fileUploader(request()->file('job_attachment'), getFilePath('jobAttachment'), null, $job->job_attachment)
                : $job->job_attachment;

            $originalName = request()->hasFile('job_attachment')
                ? request()->file('job_attachment')->getClientOriginalName()
                : $job->attachment_original_name;
        } catch (Exception) {
            $toast[] = ['error', 'File uploading process has failed'];

            return back()->with('toasts', $toast);
        }

        // Job Post
        $job->category_id              = $category->id;
        $job->subcategory_id           = $subcategory->id;
        $job->image                    = $image;
        $job->title                    = request('title');
        $job->description              = request('description');
        $job->quantity                 = request('quantity');
        $job->rate                     = request('rate');
        $job->total_budget             = $newTotalBudget ?? $oldTotalBudget;
        $job->vacancy                  = request('vacancy');
        $job->has_job_proof            = request('job_proof');
        $job->file_types               = $selectedFileTypes;
        $job->job_attachment           = $attachment;
        $job->attachment_original_name = $originalName;
        $job->save();

        // Admin Notification
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = "$user->fullname has updated an existing job.";
        $adminNotification->click_url = urlPath('admin.job.show', $job);
        $adminNotification->save();

        $toast[] = ['success', 'Job has been updated'];

        return back()->with('toasts', $toast);
    }

    function applicants(JobPost $job) {
        $response = Gate::inspect('viewApplicants', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $pageTitle = "Job Applicants";
        $user      = auth()->user();

        $job->applications()
            ->where('author_notified', ManageStatus::NO)
            ->update([
                'author_notified' => ManageStatus::YES,
            ]);

        $jobApplications = $job->applications()
            ->with(['user' => function (EloquentBuilder $builder) {
                $builder->withCount([
                    'assignedJobs as completed_jobs_count' => fn ($query) => $query->completed(),
                    'freelancerReviews as reviews_count'
                ])->withAvg('freelancerReviews as average_rating', 'rating');
            }])
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.job.applicants', compact('pageTitle', 'user', 'jobApplications', 'job'));
    }

    function pause(JobPost $job) {
        $response = Gate::inspect('pauseJob', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $job->status = ManageStatus::JOB_PAUSED;
        $job->save();

        $toast[] = ['success', 'Job has been paused'];

        return back()->with('toasts', $toast);
    }

    function resume(JobPost $job) {
        $response = Gate::inspect('resumeJob', $job);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $job->status = ManageStatus::JOB_APPROVED;
        $job->save();

        $toast[] = ['success', 'Job has been resumed'];

        return back()->with('toasts', $toast);
    }

    private function validateJobData(bool $isUpdate = false)
    {
        $this->validate(request(), [
            'image'           => [$isUpdate ? 'nullable' : 'required', File::types(['png', 'jpg', 'jpeg']), 'max:2048'],
            'category'        => 'required|integer',
            'subcategory'     => 'required|integer',
            'title'           => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'rate'            => 'required|numeric|gt:0',
            'vacancy'         => 'required|integer|min:1',
            'job_proof'       => 'required|integer|in:' . ManageStatus::JOB_PROOF_OPTIONAL . ',' . ManageStatus::JOB_PROOF_REQUIRED,
            'file_types'      => 'required_if:job_proof,' . ManageStatus::JOB_PROOF_REQUIRED . '|array',
            'file_types.*'    => 'required|string',
            'job_attachment'  => ['nullable', File::types('pdf'), 'max:5120'],
            'description'     => 'required|string|min:30',
        ], [
            'file_types.required_if' => 'The file types field is required when Job Proof is required.',
        ]);
    }

    private function ensureValidCategoryAndSubcategory()
    {
        $category    = JobCategory::active()->find(request('category'));
        $subcategory = $category?->subcategories()->active()->find(request('subcategory'));

        if (!$category || !$subcategory) {
            $toast[] = ['error', !$category ? 'Category not found' : 'Subcategory not found'];

            return back()->with('toasts', $toast)->withInput();
        }

        return [$category, $subcategory];
    }

    private function ensureValidFileTypes()
    {
        $selectedFileTypes = null;

        if (request('job_proof') == ManageStatus::JOB_PROOF_REQUIRED) {
            $fileTypeCount     = FileType::whereIn('type', request('file_types'))->active()->count();
            $selectedFileTypes = request('file_types');

            if (in_array('all', $selectedFileTypes)) array_shift($selectedFileTypes);

            if ($fileTypeCount != count($selectedFileTypes)) {
                $toast[] = ['error', 'The selected file types are invalid'];

                return back()->with('toasts', $toast)->withInput();
            }
        }

        return $selectedFileTypes;
    }
}
