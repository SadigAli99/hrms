<?php

namespace App\Enums\Vacancy;

enum EmploymentType: string
{
    case FULL = 'full_time';
    case PART = 'part_time';

    public function label(): string
    {
        return match ($this) {
            self::FULL => 'Full time',
            self::PART => 'Part time',
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
