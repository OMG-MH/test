<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssignedJob extends Model
{
    use Searchable;

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id');
    }

    public function userAssignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    public function userAssignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(JobConversation::class, 'assigned_job_id', 'id');
    }

    public function disputant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disputant_id', 'id');
    }

    /**
     * Scope a query to only include in_progress assigned-jobs.
     */
    public function scopeInProgress(Builder $query): void
    {
        $query->where('status', ManageStatus::ASSIGNED_JOB_IN_PROGRESS);
    }

    /**
     * Scope a query to only include completed assigned-jobs.
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', ManageStatus::ASSIGNED_JOB_COMPLETED);
    }

    /**
     * Scope a query to only include disputed assigned-jobs.
     */
    public function scopeDisputed(Builder $query): void
    {
        $query->where('status', ManageStatus::ASSIGNED_JOB_DISPUTED);
    }

    /**
     * Scope a query to only include settled assigned-jobs.
     */
    public function scopeSettled(Builder $query): void
    {
        $query->where('status', ManageStatus::ASSIGNED_JOB_SETTLED);
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::ASSIGNED_JOB_DISPUTED  => '<span class="badge badge--danger">' . trans('Disputed') . '</span>',
                    ManageStatus::ASSIGNED_JOB_COMPLETED => '<span class="badge badge--success">' . trans('Completed') . '</span>',
                    ManageStatus::ASSIGNED_JOB_SETTLED   => '<span class="badge badge--primary">' . trans('Settled') . '</span>',
                    default                              => '<span class="badge badge--info">' . trans('In Progress') . '</span>',
                };
            },
        );
    }
}
