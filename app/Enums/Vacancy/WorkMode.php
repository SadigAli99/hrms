<?php

namespace App\Enums\Vacancy;

enum WorkMode: string
{
    case ONSITE = 'onsite';
    case REMOTE = 'remote';
    case HYBRID = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::ONSITE => 'Onsite',
            self::REMOTE => 'Remote',
            self::HYBRID => 'Hybrid',
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
