<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AssignedJob;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class AssignedJobController extends Controller
{
    function index() {
        $pageTitle    = "Assigned Jobs";
        $user         = auth()->user();
        $assignedJobs = $user->assignedJobsBy()
            ->where(function (Builder $query) {
                $query->where('status', ManageStatus::ASSIGNED_JOB_IN_PROGRESS)
                    ->orWhere('status', ManageStatus::ASSIGNED_JOB_COMPLETED);
            })
            ->with(['job:id,title', 'userAssignedTo'])
            ->searchable(['job:title', 'userAssignedTo:firstname', 'userAssignedTo:lastname'])
            ->latest()
            ->paginate(getPaginate());

        $assignedJobs->getCollection()->transform(function($assignedJob) use ($user) {
            $assignedJob['review_exists'] = $this->hasReviewFromAuthor($user, $assignedJob);

            return $assignedJob;
        });

        return view($this->activeTheme . 'user.job.assignedJobs', compact('pageTitle', 'user', 'assignedJobs'));
    }

    function disputedJobs() {
        $pageTitle    = "Disputed Jobs";
        $user         = auth()->user();
        $disputedJobs = $user->assignedJobsBy()
            ->where(function (Builder $query) {
                $query->where('status', ManageStatus::ASSIGNED_JOB_DISPUTED)
                    ->orWhere('status', ManageStatus::ASSIGNED_JOB_SETTLED);
            })
            ->with(['job:id,title', 'userAssignedTo', 'disputant'])
            ->searchable(['job:title', 'userAssignedTo:firstname', 'userAssignedTo:lastname'])
            ->latest()
            ->paginate(getPaginate());

        $disputedJobs->getCollection()->transform(function($disputedJob) use ($user) {
            $disputedJob['review_exists'] = $this->hasReviewFromAuthor($user, $disputedJob);

            return $disputedJob;
        });

        return view($this->activeTheme . 'user.job.disputedJobs', compact('pageTitle', 'user', 'disputedJobs'));
    }

    function show(AssignedJob $assignedJob) {
        $response = Gate::inspect('view', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $pageTitle = "Job Info";
        $user      = auth()->user();

        $assignedJob->load('job', 'userAssignedTo');

        $conversations = $assignedJob->conversations()->orderBy('created_at')->get();

        return view($this->activeTheme . 'user.job.jobShow', compact('pageTitle', 'assignedJob', 'user', 'conversations'));
    }

    function complete(AssignedJob $assignedJob) {
        $response = Gate::inspect('complete', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        if ($assignedJob->status != ManageStatus::ASSIGNED_JOB_IN_PROGRESS) {
            $toast[] = ['error', 'The job is not in a pending state'];

            return back()->with('toasts', $toast);
        }

        $assignedJob->load(['job', 'userAssignedTo']);

        $assignedJob->status       = ManageStatus::ASSIGNED_JOB_COMPLETED;
        $assignedJob->completed_at = now();
        $assignedJob->save();

        // Transfer the balance to the freelancer's account
        $job                    = $assignedJob->job;
        $freelancerCompensation = $job->quantity * $job->rate;
        $freelancer             = $assignedJob->userAssignedTo;

        $freelancer->increment('balance', $freelancerCompensation);
        $freelancer->refresh();

        $currency = bs('site_cur');
        $amount   = showAmount($freelancerCompensation);

        // Create transaction
        $transaction               = new Transaction();
        $transaction->user_id      = $freelancer->id;
        $transaction->amount       = $freelancerCompensation;
        $transaction->post_balance = $freelancer->balance;
        $transaction->trx_type     = '+';
        $transaction->trx          = getTrx();
        $transaction->details      = "Earned $amount $currency for completing the '{$assignedJob->job->title}' job.";
        $transaction->remark       = 'job_earning';
        $transaction->save();

        // Notify freelancer
        notify($freelancer, 'ASSIGNED_JOB_COMPLETE', [
            'job_code' => $job->job_code,
            'title'    => $job->title,
            'amount'   => $amount,
        ]);

        $toast[] = ['success', 'This job has been marked as completed'];

        return back()->with('toasts', $toast);
    }

    function dispute(AssignedJob $assignedJob) {
        $response = Gate::inspect('dispute', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $this->validate(request(), [
            'dispute_reason' => 'required|string|min:10|max:1000',
        ]);

        if ($assignedJob->status != ManageStatus::ASSIGNED_JOB_IN_PROGRESS) {
            $toast[] = ['error', 'The job is not in progress state'];

            return back()->with('toasts', $toast);
        }

        $assignedJob->load(['job', 'userAssignedTo']);
        $user = auth()->user();
        $job  = $assignedJob->job;

        $assignedJob->status         = ManageStatus::ASSIGNED_JOB_DISPUTED;
        $assignedJob->disputant_id   = $user->id;
        $assignedJob->dispute_reason = request('dispute_reason');
        $assignedJob->disputed_at    = now();
        $assignedJob->save();

        // Notify freelancer
        notify($assignedJob->userAssignedTo, 'ASSIGNED_JOB_DISPUTE', [
            'job_code' => $job->job_code,
            'title'    => $job->title,
            'reason'   => request('dispute_reason'),
        ]);

        // Notify admin
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = "$user->fullname has raised a dispute for the job titled '$job->title'.";
        $adminNotification->click_url = urlPath('admin.job.dispute.show', [$job, $assignedJob->id]);
        $adminNotification->save();

        $toast[] = ['success', 'This job has been marked as disputed'];

        return back()->with('toasts', $toast);
    }

    function shareFeedback(AssignedJob $assignedJob) {
        $response = Gate::inspect('review', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $this->validate(request(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        $user           = auth()->user();
        $isReviewExists = $this->hasReviewFromAuthor($user, $assignedJob);

        if ($isReviewExists) {
            $toast[] = ['error', 'You have already reviewed this'];

            return back()->with('toasts', $toast);
        }

        $user->authoredReviews()->create([
            'freelancer_id' => $assignedJob->assigned_to,
            'job_id'        => $assignedJob->job_id,
            'rating'        => request('rating'),
            'review'        => request('review'),
        ]);

        $toast[] = ['success', 'Your feedback has been shared'];

        return back()->with('toasts', $toast);
    }

    private function hasReviewFromAuthor(User $user, AssignedJob $assignedJob)
    {
        return $user->authoredReviews()
            ->where([
                ['freelancer_id', $assignedJob->assigned_to],
                ['job_id', $assignedJob->job_id]
            ])
            ->exists();
    }
}
