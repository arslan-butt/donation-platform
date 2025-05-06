<?php

namespace App\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_map(fn ($case) => [
            'label' => str_replace('_', ' ', ucfirst($case->name)),
            'value' => $case->value,
        ], self::cases());
    }
}
