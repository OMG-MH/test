<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AssignedJob;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\Transaction;
use Illuminate\Support\Facades\Gate;

class JobApplicationController extends Controller
{
    function applicationShow(JobPost $job, JobApplication $jobApplication) {
        $jobApplication->load(['job', 'user']);

        $response = Gate::inspect('view', $jobApplication);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return to_route('user.job.history')->with('toasts', $toast);
        }

        $pageTitle = "Job Application";
        $user      = auth()->user();

        return view($this->activeTheme . 'user.job.applicationShow', compact('pageTitle', 'user', 'jobApplication'));
    }

    function applicationAccept(JobPost $job, JobApplication $jobApplication) {
        $jobApplication->load(['user', 'job']);

        $response = Gate::inspect('accept', $jobApplication);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return to_route('user.job.history')->with('toasts', $toast);
        }

        if (!$jobApplication->job->vacancy) {
            $toast[] = ['error', 'There are no vacancies for this job'];

            return back()->with('toasts', $toast);
        }

        // accept the job application
        $jobApplication->status = ManageStatus::JOB_APPLICATION_APPROVED;
        $jobApplication->save();

        // assign the job to the applicant
        $assignedJob              = new AssignedJob();
        $assignedJob->job_id      = $jobApplication->job_id;
        $assignedJob->assigned_by = auth()->id();
        $assignedJob->assigned_to = $jobApplication->user_id;
        $assignedJob->save();

        // update job
        $jobPost = $jobApplication->job;
        $jobPost->decrement('vacancy');
        $jobPost->refresh();

        if (!$jobPost->vacancy) {
            $jobPost->status = ManageStatus::JOB_UNAVAILABLE;
            $jobPost->save();
        }

        // deduct job cost from job author
        $jobCost = $jobPost->quantity * $jobPost->rate;
        $jobPost->user->decrement('balance', $jobCost);

        // transaction
        $transaction               = new Transaction();
        $transaction->user_id      = $jobPost->user->id;
        $transaction->amount       = $jobCost;
        $transaction->post_balance = $jobPost->user->balance;
        $transaction->trx_type     = '-';
        $transaction->trx          = $jobPost->job_code;
        $transaction->details      = "Hired {$jobApplication->user->fullname} for the job titled '$jobPost->title'";
        $transaction->remark       = 'hire_employee';
        $transaction->save();

        // notify the applicant
        notify($jobApplication->user, 'JOB_APPLICATION_APPROVE', [
            'job_code' => $jobPost->job_code,
            'title'    => $jobPost->title,
        ]);

        $toast[] = ['success', 'The job application has been accepted'];

        return back()->with('toasts', $toast);
    }

    function applicationReject(JobPost $job, JobApplication $jobApplication) {
        $jobApplication->load(['user', 'job']);

        $response = Gate::inspect('reject', $jobApplication);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return to_route('user.job.history')->with('toasts', $toast);
        }

        // reject the job application
        $jobApplication->status = ManageStatus::JOB_APPLICATION_REJECTED;
        $jobApplication->save();

        // notify the applicant
        notify($jobApplication->user, 'JOB_APPLICATION_REJECT', [
            'title' => $jobApplication->job->title,
        ]);

        $toast[] = ['success', 'The job application has been rejected'];

        return back()->with('toasts', $toast);
    }
}
