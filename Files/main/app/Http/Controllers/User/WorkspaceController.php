<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\AssignedJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class WorkspaceController extends Controller
{
    function appliedJobs() {
        $pageTitle    = "Applied Jobs";
        $user         = auth()->user();
        $applications = $user->jobApplications()
            ->with('job:id,job_code,title,quantity,rate')
            ->select(['id', 'job_id', 'status', 'created_at'])
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.workspace.appliedJobs', compact('pageTitle', 'user', 'applications'));
    }

    function ongoingJobs() {
        $pageTitle   = "Ongoing Jobs";
        $user        = auth()->user();
        $ongoingJobs = $this->jobData($user, 'inProgress');

        return view($this->activeTheme . 'user.workspace.ongoingJobs', compact('pageTitle', 'user', 'ongoingJobs'));
    }

    function ongoingJobDispute(int $id) {
        $assignedJob = AssignedJob::with(['job', 'userAssignedBy'])
            ->where('id', $id)
            ->inProgress()
            ->firstOrFail();

        $response = Gate::inspect('dispute', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $this->validate(request(), [
            'dispute_reason' => 'required|string|min:10|max:1000',
        ]);

        $user = auth()->user();
        $job  = $assignedJob->job;

        $assignedJob->status         = ManageStatus::ASSIGNED_JOB_DISPUTED;
        $assignedJob->disputant_id   = $user->id;
        $assignedJob->dispute_reason = request('dispute_reason');
        $assignedJob->disputed_at    = now();
        $assignedJob->save();

        // Notify job author
        notify($assignedJob->userAssignedBy, 'ASSIGNED_JOB_DISPUTE', [
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

    function completedJobs() {
        $pageTitle     = "Completed Jobs";
        $user          = auth()->user();
        $completedJobs = $this->jobData($user, 'completed');

        return view($this->activeTheme . 'user.workspace.completedJobs', compact('pageTitle', 'user', 'completedJobs'));
    }

    function disputedJobs() {
        $pageTitle    = "Disputed Jobs";
        $user         = auth()->user();
        $disputedJobs = $user->assignedJobs()
            ->with(['job:id,job_code,title', 'userAssignedBy', 'disputant'])
            ->where(function (Builder $query) {
                $query->where('status', ManageStatus::ASSIGNED_JOB_DISPUTED)
                    ->orWhere('status', ManageStatus::ASSIGNED_JOB_SETTLED);
            })
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.workspace.disputedJobs', compact('pageTitle', 'user', 'disputedJobs'));
    }

    private function jobData(User $user, string $scope)
    {
        return $user->assignedJobs()
            ->with(['job:id,job_code,title', 'userAssignedBy'])
            ->$scope()
            ->latest()
            ->paginate(getPaginate());
    }

    function jobShow(int $id) {
        $assignedJob = AssignedJob::with(['job' => function ($query) {
            $query->select(['id', 'category_id', 'subcategory_id', 'job_code', 'image', 'title', 'quantity', 'rate'])
                ->with(['category:id,name', 'subcategory:id,name']);
        }, 'userAssignedBy'])->findOrFail($id);

        $response = Gate::inspect('view', $assignedJob);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $pageTitle     = "Job Info";
        $user          = auth()->user();
        $conversations = $assignedJob->conversations()->orderBy('created_at')->get();

        return view($this->activeTheme . 'user.workspace.jobShow', compact('pageTitle', 'user', 'conversations', 'assignedJob'));
    }
}
