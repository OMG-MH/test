<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\AssignedJob;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AssignedJobPolicy
{
    public function view(User $user, AssignedJob $assignedJob): Response
    {
        return $user->id === $assignedJob->assigned_by || $user->id === $assignedJob->assigned_to
            ? Response::allow()
            : Response::deny('You do not have permission to view this job.');
    }

    public function complete(User $user, AssignedJob $assignedJob): Response
    {
        return $user->id === $assignedJob->assigned_by
            ? Response::allow()
            : Response::deny('You do not have permission to complete this job.');
    }

    public function dispute(User $user, AssignedJob $assignedJob): Response
    {
        return $user->id === $assignedJob->assigned_by || $user->id === $assignedJob->assigned_to
            ? Response::allow()
            : Response::deny('You do not have permission to dispute this job.');
    }

    public function review(User $user, AssignedJob $assignedJob): Response
    {
        return $user->id === $assignedJob->assigned_by && $assignedJob->status !== ManageStatus::ASSIGNED_JOB_IN_PROGRESS
            ? Response::allow()
            : Response::deny('You do not have permission to review this assigned job.');
    }
}
