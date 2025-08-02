<?php

namespace App\Models;

use App\Constants\ManageStatus;
use HTMLPurifier;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id');
    }

    /**
     * Interact with the applicant bio.
     */
    protected function applicantBio(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => (new HTMLPurifier())->purify($value),
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
                    ManageStatus::JOB_APPLICATION_REJECTED => '<span class="badge badge--danger">' . trans('Rejected') . '</span>',
                    ManageStatus::JOB_APPLICATION_APPROVED => '<span class="badge badge--success">' . trans('Approved') . '</span>',
                    default                                => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                };
            },
        );
    }
}
