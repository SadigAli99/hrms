<?php

namespace App\Enums\Vacancy;

enum RequirementType: string
{
    case SKILL = 'skill';
    case EXPERIENCE = 'experience';
    case LANGUAGE = 'language';
    case EDUCATION = 'education';
    case TOOL = 'tool';
    case CERTIFICATION = 'certification';

    public function label(): string
    {
        return match ($this) {
            self::SKILL => 'Bilik və bacarıqlar',
            self::EXPERIENCE => 'Təcrübə',
            self::LANGUAGE => 'Dil',
            self::EDUCATION => 'Təhsil',
            self::TOOL => 'Alət',
            self::CERTIFICATION => 'Sertifikatlaşdırma'
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
