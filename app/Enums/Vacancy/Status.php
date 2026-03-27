<?php

namespace App\Enums\Vacancy;

enum Status: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case REVIEW = 'in_review';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::REVIEW => 'In Review',
            self::CLOSED => 'Closed',
            self::ARCHIVED => 'Archived'
        };
    }

    public function color() : string {
        return match ($this) {
            self::DRAFT => 'badge-yellow',
            self::PUBLISHED => 'badge-green',
            self::REVIEW => 'badge-cyan',
            self::CLOSED => 'badge-red',
            self::ARCHIVED => 'badge-yellow',
        };
    }

    public static function getValues(): array
    {
        $options = [];
        foreach (self::cases() as $item) {
            $options[$item->value] = $item->label();
        }

        return $options;
    }
}
