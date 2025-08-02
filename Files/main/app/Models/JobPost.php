<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use HTMLPurifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPost extends Model
{
    use Searchable;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'job_code';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id', 'id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(JobSubcategory::class, 'subcategory_id', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id', 'id');
    }

    public function assignedJobs(): HasMany
    {
        return $this->hasMany(AssignedJob::class, 'job_id', 'id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(FreelancerReview::class, 'job_id', 'id');
    }

    /**
     * Scope a query to only include rejected jobs.
     */
    public function scopeRejected(Builder $query): void
    {
        $query->where('status', ManageStatus::JOB_REJECTED);
    }

    /**
     * Scope a query to only include approved jobs.
     */
    public function scopeApproved(Builder $query): void
    {
        $query->where('status', ManageStatus::JOB_APPROVED);
    }

    /**
     * Scope a query to only include pending jobs.
     */
    public function scopePending(Builder $query): void
    {
        $query->where('status', ManageStatus::JOB_PENDING);
    }

    /**
     * Scope a query to only include paused jobs.
     */
    public function scopePaused(Builder $query): void
    {
        $query->where('status', ManageStatus::JOB_PAUSED);
    }

    /**
     * Scope a query to only include unavailable jobs.
     */
    public function scopeUnavailable(Builder $query): void
    {
        $query->where('status', ManageStatus::JOB_UNAVAILABLE);
    }

    /**
     * Interact with the description.
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => (new HTMLPurifier())->purify($value),
        );
    }

    /**
     * Get the job proof's accepted file types.
     */
    protected function fileTypes(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? explode(',', $value) : [],
            set: fn (?array $value)  => $value ? implode(',', $value) : null,
        );
    }

    /**
     * Get the status type.
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->status) {
                    ManageStatus::JOB_REJECTED    => '<span class="badge badge--danger">' . trans('Rejected') . '</span>',
                    ManageStatus::JOB_APPROVED    => '<span class="badge badge--success">' . trans('Approved') . '</span>',
                    ManageStatus::JOB_PAUSED      => '<span class="badge badge--info">' . trans('Paused') . '</span>',
                    ManageStatus::JOB_UNAVAILABLE => '<span class="badge badge--secondary">' . trans('Vacancy Full') . '</span>',
                    default                       => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                };
            },
        );
    }
}
