<?php

namespace App\Policies;

use App\Models\AssignedJob;
use App\Models\JobConversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobConversationPolicy
{
    public function communicate(User $user, AssignedJob $assignedJob): Response
    {
        return $user->id === $assignedJob->assigned_by || $user->id === $assignedJob->assigned_to
            ? Response::allow()
            : Response::deny('You do not have permission to perform this action.');
    }

    public function download(User $user, JobConversation $conversation): Response
    {
        $assignedJob = $conversation->assignedJob;

        return $user->id === $assignedJob->assigned_by || $user->id === $assignedJob->assigned_to
            ? Response::allow()
            : Response::deny('You do not have permission to download this file.');
    }
}
