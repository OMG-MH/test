<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreelancerReview extends Model
{
    protected $fillable = [
        'author_id',
        'freelancer_id',
        'job_id',
        'rating',
        'review',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPost::class, 'job_id', 'id');
    }
}
