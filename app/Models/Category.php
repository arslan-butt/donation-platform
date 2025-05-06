<?php

namespace App\Models;

use App\Traits\HasAuthor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasAuthor, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Campaigns associated with this category.
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public static function getForDropdown(): array
    {
        return self::query()
            ->select('id', 'name', 'description')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}
