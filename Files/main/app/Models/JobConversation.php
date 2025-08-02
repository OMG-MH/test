<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobConversation extends Model
{
    public function assignedJob(): BelongsTo
    {
        return $this->belongsTo(AssignedJob::class, 'assigned_job_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}
