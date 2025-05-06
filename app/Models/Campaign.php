<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Traits\HasAuthor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasAuthor, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'initial_amount',
        'start_date',
        'end_date',
        'status',
        'type',
        'featured_image',
        'category_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'target_amount' => 'decimal:2',
        'initial_amount' => 'decimal:2',
        'status' => CampaignStatus::class,
    ];

    protected $appends = ['formatted_target_amount', 'progress_percentage'];
    // Relationships

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    // Accessors
    public function getFormattedTargetAmountAttribute(): string
    {
        return number_format($this->target_amount, 2);
    }

    public function getProgressPercentageAttribute(): float
    {
        return $this->target_amount > 0
            ? round(($this->total_amount / $this->target_amount) * 100, 2)
            : 0;
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image
            ? asset("storage/{$this->featured_image}")
            : null;
    }
    // Scopes / helpers

    public function isMission(): bool
    {
        return $this->type === 'admin';
    }

    public function isCampaign(): bool
    {
        return $this->type === 'employee';
    }

    public function scopeWhereStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithDonationStats(Builder $query): void
    {
        $query->select(['campaigns.*'])
            ->selectRaw('
            IFNULL((
                SELECT SUM(amount)
                FROM donations
                WHERE donations.campaign_id = campaigns.id
                AND donations.status = "completed"
            ), 0) AS total_donation_amount
        ')
            ->selectRaw('
            campaigns.initial_amount +
            IFNULL((
                SELECT SUM(amount)
                FROM donations
                WHERE donations.campaign_id = campaigns.id
                AND donations.status = "completed"
            ), 0) AS total_amount
        ');
    }

    public function scopeSearch(Builder $query, ?string $search): void
    {
        $query->when($search, function ($q) use ($search) {
            // Use full-text index if available
            if (config('database.default') === 'mysql') {
                $q->whereRaw('MATCH(title, description) AGAINST(? IN BOOLEAN MODE)', [$search]);
            } else {
                $q->where('title', 'like', "{$search}%")
                    ->orWhere('description', 'like', "{$search}%");
            }
        });
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when($filters['category_id'] ?? null, fn ($q, $id) => $q->where('category_id', $id))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['featured'] ?? false, fn ($q) => $q->whereNotNull('featured_image'))
            ->when($filters['date_range'] ?? null, fn ($q, $range) => $q->whereBetween('start_date', [
                Carbon::parse($range[0])->startOfDay(),
                Carbon::parse($range[1])->endOfDay(),
            ]));
    }

    public function scopeFilterByFeatured(Builder $query, bool $featured): void
    {
        $query->when($featured, function ($query) {
            $query->whereNotNull('featured_image');
        });
    }

    public function scopeFilterByDateRange(Builder $query, ?array $dateRange): void
    {
        $query->when($dateRange && count($dateRange) === 2, function ($query) use ($dateRange) {
            $query->whereBetween('start_date', [
                Carbon::parse($dateRange[0])->startOfDay(),
                Carbon::parse($dateRange[1])->endOfDay(),
            ]);
        });
    }

    public function scopeForUser(Builder $query, User $user): void
    {
        $query->when(! $user->hasRole('admin'), function ($query) use ($user) {
            $query->where('created_by', $user->id);
        });
    }

    public function formatForResponse(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'target_amount' => number_format((float) $this->target_amount, 2),
            'initial_amount' => number_format((float) $this->initial_amount, 2),
            'total_donation_amount' => number_format((float) $this->total_donation_amount, 2),
            'total_amount' => number_format((float) $this->total_amount, 2),
            'progress' => $this->target_amount > 0
                ? number_format(($this->total_amount / $this->target_amount) * 100, 2)
                : 0,
            'start_date' => $this->start_date->toDateString(),
            'end_date' => $this->end_date->toDateString(),
            'status' => $this->status,
            'featured' => $this->featured_image_url,
            'author' => $this->author->name,
            'category' => $this->category?->name,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
