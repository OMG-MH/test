<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobSubcategory extends Model
{
    use Searchable, UniversalStatus;

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id', 'id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(JobPost::class, 'subcategory_id', 'id');
    }
}
