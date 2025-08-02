<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Constants\ManageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token', 'ver_code', 'balance', 'kyc_data', 'kyf_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'address'           => 'object',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime',
        'kyf_data'          => 'object',
    ];

    /**
     * Get the user's full name.
     */
    public function fullname(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    /**
     * Interact with the user's skills.
     */
    protected function skills(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? explode(',', $value) : [],
            set: fn (array $value)   => implode(',', $value),
        );
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', ManageStatus::PAYMENT_INITIATE);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(JobPost::class, 'user_id', 'id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'user_id', 'id');
    }

    public function assignedJobsBy(): HasMany
    {
        return $this->hasMany(AssignedJob::class, 'assigned_by', 'id');
    }

    public function assignedJobs(): HasMany
    {
        return $this->hasMany(AssignedJob::class, 'assigned_to', 'id');
    }

    public function authoredReviews(): HasMany
    {
        return $this->hasMany(FreelancerReview::class, 'author_id', 'id');
    }

    public function freelancerReviews(): HasMany
    {
        return $this->hasMany(FreelancerReview::class, 'freelancer_id', 'id');
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(AssignedJob::class, 'disputant_id', 'id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(JobConversation::class, 'sender_id', 'id');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', ManageStatus::ACTIVE)->where('ec', ManageStatus::VERIFIED)->where('sc', ManageStatus::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', ManageStatus::INACTIVE);
    }

    public function scopeEmailUnconfirmed($query)
    {
        return $query->where('ec', ManageStatus::UNVERIFIED);
    }

    public function scopeMobileUnconfirmed($query)
    {
        return $query->where('sc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycUnconfirmed($query)
    {
        return $query->where('kc', ManageStatus::UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kc', ManageStatus::PENDING);
    }

    /**
     * Scope a query to only include rejected freelancers.
     */
    public function scopeRejectedFreelancer(Builder $query): void
    {
        $query->where('freelancer_status', ManageStatus::FREELANCER_REJECTED);
    }

    /**
     * Scope a query to only include active freelancers.
     */
    public function scopeActiveFreelancer(Builder $query): void
    {
        $query->where('freelancer_status', ManageStatus::FREELANCER_ACTIVE);
    }

    /**
     * Scope a query to only include pending freelancers.
     */
    public function scopePendingFreelancer(Builder $query): void
    {
        $query->where('freelancer_status', ManageStatus::FREELANCER_PENDING);
    }

    /**
     * Scope a query to only include banned freelancers.
     */
    public function scopeBannedFreelancer(Builder $query): void
    {
        $query->where('freelancer_status', ManageStatus::FREELANCER_BANNED);
    }

    /**
     * Get the freelancer status type.
     */
    protected function freelancerStatusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->freelancer_status) {
                    ManageStatus::FREELANCER_REJECTED => '<span class="badge badge--danger">' . trans('Rejected') . '</span>',
                    ManageStatus::FREELANCER_ACTIVE   => '<span class="badge badge--success">' . trans('Active') . '</span>',
                    ManageStatus::FREELANCER_BANNED   => '<span class="badge badge--secondary">' . trans('Banned') . '</span>',
                    default                           => '<span class="badge badge--warning">' . trans('Pending') . '</span>',
                };
            },
        );
    }
}
