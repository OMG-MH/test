<?php

namespace App\Models;

use App\Constants\ManageStatus;
use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobCategory extends Model
{
    use Searchable, UniversalStatus;

    public function subcategories(): HasMany
    {
        return $this->hasMany(JobSubcategory::class, 'job_category_id', 'id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(JobPost::class, 'category_id', 'id');
    }

    /**
     * Scope a query to only include featured categories.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', ManageStatus::YES);
    }

    /**
     * Get the featured status.
     */
    protected function featuredBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->is_featured) {
                    ManageStatus::YES => '<span class="badge badge--success">' . trans('Yes') . '</span>',
                    default           => '<span class="badge badge--warning">' . trans('No') . '</span>',
                };
            },
        );
    }
}
