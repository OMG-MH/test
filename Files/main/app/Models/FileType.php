<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UniversalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use Searchable, UniversalStatus;

    /**
     * Interact with the file types.
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => str_starts_with($value, '.') ? $value : '.' . $value,
        );
    }
}
