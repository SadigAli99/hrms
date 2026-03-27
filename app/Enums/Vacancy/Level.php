<?php

namespace App\Enums\Vacancy;

enum Level: string
{
    case INTERN = 'internship';
    case JUNIOR = 'junior';
    case MIDDLE = 'middle';
    case SENIOR = 'senior';
    case LEAD = 'lead';

    public function label()
    {
        return match ($this) {
            self::INTERN => 'Internship',
            self::JUNIOR => 'Junior',
            self::MIDDLE => 'Middle',
            self::SENIOR => 'Senior',
            self::LEAD => 'Lead'
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
