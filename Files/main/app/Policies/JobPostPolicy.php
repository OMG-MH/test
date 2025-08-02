<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPostPolicy
{
    public function edit(User $user, JobPost $job): Response
    {
        return $user->id === $job->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to edit this job.');
    }

    public function download(User $user, JobPost $job): Response
    {
        return $user->id === $job->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to download this attachment.');
    }

    public function update(User $user, JobPost $job): Response
    {
        return $user->id === $job->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this job.');
    }

    public function viewApplicants(User $user, JobPost $job) : Response
    {
        return $user->id === $job->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to view the applicants of this job.');
    }

    public function pauseJob(User $user, JobPost $job): Response
    {
        if ($user->id !== $job->user_id) {
            return Response::deny('You do not have permission to pause this job.');
        }

        if ($job->status !== ManageStatus::JOB_APPROVED) {
            return Response::deny('This job is not currently active.');
        }

        return Response::allow();
    }

    public function resumeJob(User $user, JobPost $job): Response
    {
        if ($user->id !== $job->user_id) {
            return Response::deny('You do not have permission to resume this job.');
        }

        if ($job->status !== ManageStatus::JOB_PAUSED) {
            return Response::deny('This job is not currently paused.');
        }

        return Response::allow();
    }
}
