<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasAuthor
{
    /**
     * The "booted" method of the model.
     */
    public static function bootHasAuthor(): void
    {
        static::creating(function ($model): void {
            $userId = Auth::id();

            if (! $model->isDirty('created_by') && $userId !== null) {
                $model->created_by = $userId;
            }

            if (! $model->isDirty('updated_by') && $userId !== null) {
                $model->updated_by = $userId;
            }
        });

        static::updating(function ($model): void {
            $userId = Auth::id();
            if (! $model->isDirty('updated_by')) {
                $model->updated_by = $userId;
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
