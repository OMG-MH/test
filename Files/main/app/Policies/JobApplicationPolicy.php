<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobApplicationPolicy
{
    public function apply(User $user, JobPost $job): Response
    {
        if ($this->isUserJobAuthor($user, $job)) {
            return Response::deny('You are not allowed to apply for this job.');
        }

        if (!$this->isVacancyAvailable($job)) {
            return Response::deny('There is no vacancy available for this job.');
        }

        if (!$this->isJobApproved($job)) {
            return Response::deny('The job is not approved.');
        }

        if ($this->hasUserAlreadyApplied($user, $job)) {
            return Response::deny('You have already applied for this job.');
        }

        if ($this->isBannedFreelancer($user)) {
            return Response::deny('You are banned from applying for jobs.');
        }

        if (!$this->isVerifiedFreelancer($user)) {
            return Response::deny('You must be a verified freelancer to apply for this job.');
        }

        return Response::allow();
    }

    public function view(User $user, JobApplication $jobApplication): Response
    {
        return $this->isUserJobAuthor($user, $jobApplication->job)
            ? Response::allow()
            : Response::deny('You are not allowed to view this job application.');
    }

    public function accept(User $user, JobApplication $jobApplication): Response
    {
        if (!$this->isUserJobAuthor($user, $jobApplication->job)) {
            return Response::deny('You are not allowed to accept this job application.');
        }

        if (!$this->isApplicationPending($jobApplication)) {
            return Response::deny('The job application is not in a pending state.');
        }

        $job                    = $jobApplication->job;
        $freelancerCompensation = $job->quantity * $job->rate;

        if ($user->balance < $freelancerCompensation) {
            return Response::deny('You don\'t have enough balance to accept this job application.');
        }

        return Response::allow();
    }

    public function reject(User $user, JobApplication $jobApplication): Response
    {
        return $this->isUserJobAuthor($user, $jobApplication->job) && $this->isApplicationPending($jobApplication)
            ? Response::allow()
            : Response::deny('You are not allowed to reject this job application.');
    }

    public function isUserJobAuthor(User $user, JobPost $job): bool
    {
        return $user->id === $job->user_id;
    }

    public function isVacancyAvailable(JobPost $job): bool
    {
        return $job->vacancy > 0;
    }

    public function isJobApproved(JobPost $job): bool
    {
        return $job->status === ManageStatus::JOB_APPROVED;
    }

    public function hasUserAlreadyApplied(User $user, JobPost $job): bool
    {
        return $job->applications()
            ->where([
                ['user_id', $user->id],
                ['status', ManageStatus::JOB_APPLICATION_PENDING]
            ])
            ->exists();
    }

    protected function isApplicationPending(JobApplication $jobApplication): bool
    {
        return $jobApplication->status === ManageStatus::JOB_APPLICATION_PENDING;
    }

    protected function isBannedFreelancer(User $user): bool
    {
        return $user->freelancer_status === ManageStatus::FREELANCER_BANNED;
    }

    protected function isVerifiedFreelancer(User $user): bool
    {
        return $user->freelancer_status === ManageStatus::FREELANCER_ACTIVE;
    }
}
