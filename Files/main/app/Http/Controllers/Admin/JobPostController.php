<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\AssignedJob;
use App\Models\JobPost;

class JobPostController extends Controller
{
    function index() {
        $pageTitle = "All Jobs";
        $jobs      = $this->jobData();

        return view('admin.job.index', compact('pageTitle', 'jobs'));
    }

    function pending() {
        $pageTitle = "Pending Jobs";
        $jobs      = $this->jobData('pending');

        return view('admin.job.index', compact('pageTitle', 'jobs'));
    }

    function approved() {
        $pageTitle = "Approved Jobs";
        $jobs      = $this->jobData('approved');

        return view('admin.job.index', compact('pageTitle', 'jobs'));
    }

    function rejected() {
        $pageTitle = "Rejected Jobs";
        $jobs      = $this->jobData('rejected');

        return view('admin.job.index', compact('pageTitle', 'jobs'));
    }

    function unavailable() {
        $pageTitle = "Unavailable Jobs";
        $jobs      = $this->jobData('unavailable');

        return view('admin.job.index', compact('pageTitle', 'jobs'));
    }

    function disputedJobs() {
        $pageTitle = "Disputed Jobs";
        $disputedJobs = AssignedJob::disputed()->latest()->with(['job', 'disputant'])->paginate(getPaginate());

        return view('admin.job.disputeIndex', compact('pageTitle', 'disputedJobs'));
    }

    protected function jobData(string $scope = null) {
        $jobQuery = $scope ? JobPost::$scope() : JobPost::query();

        return $jobQuery->with(['user', 'category:id,name'])
            ->searchable(['job_code', 'title', 'category:name', 'user:username'])
            ->latest()
            ->paginate(getPaginate());
    }

    function show(JobPost $job) {
        $pageTitle = 'Job Details';
        $job       = $job->load(['user', 'category:id,name', 'subcategory:id,name']);

        return view('admin.job.show', compact('pageTitle', 'job'));
    }

    function applicants(JobPost $job) {
        $pageTitle  = "Job Applicants for '$job->title'";
        $applicants = $job->applications()->with('user')->latest()->paginate(getPaginate());

        return view('admin.job.applicants', compact('pageTitle', 'applicants'));
    }

    function approve(JobPost $job) {
        if ($job->status != ManageStatus::JOB_PENDING) {
            $toast[] = ['error', 'This job is no longer in the pending state'];

            return back()->with('toasts', $toast);
        }

        $job->status = ManageStatus::JOB_APPROVED;
        $job->save();

        notify($job->user, 'JOB_APPROVE', [
            'job_code' => $job->job_code,
            'title'    => $job->title,
            'amount'   => showAmount($job->total_budget),
        ]);

        $toast[] = ['success', 'The job has been approved'];

        return back()->with('toasts', $toast);
    }

    function reject(JobPost $job) {
        if ($job->status != ManageStatus::JOB_PENDING) {
            $toast[] = ['error', 'This job is no longer in the pending state'];

            return back()->with('toasts', $toast);
        }

        $job->status = ManageStatus::JOB_REJECTED;
        $job->save();

        notify($job->user, 'JOB_REJECT', [
            'job_code' => $job->job_code,
            'title'    => $job->title,
        ]);

        $toast[] = ['success', 'The job has been rejected'];

        return back()->with('toasts', $toast);
    }
}
