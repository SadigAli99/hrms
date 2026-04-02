<?php

namespace App\Enums\TalentPool;

enum Category: string
{
    case RECOMMENDED = 'recommended';
    case WATCHLIST = 'watchlist';
    case FUTURE_FIT = 'future_fit';

    public function label(): string
    {
        return match ($this) {
            self::RECOMMENDED => 'Tövsiyə olunan',
            self::WATCHLIST => 'İzləmə siyahısı',
            self::FUTURE_FIT => 'Gələcək uyğunluq'
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::RECOMMENDED => 'Oxşar vakansiyalar üçün yaxın zamanda yenidən baxıla biləcək güclü profildir.',
            self::WATCHLIST => 'Saxlanmağa dəyərdir, amma yaxın müddət üçün əsas prioritet deyil.',
            self::FUTURE_FIT => 'Bu rol üçün tam uyğun deyil, amma sonradan başqa vakansiya üçün faydalı ola bilər.'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::RECOMMENDED => 'badge-green',
            self::WATCHLIST => 'badge-cyan',
            self::FUTURE_FIT => 'badge-yellow'
        };
    }

    public static function getValues(): array
    {
        $options = [];
        foreach (self::cases() as $item) {
            $options[$item->value] = [
                'label' => $item->label(),
                'description' => $item->description()
            ];
        }

        return $options;
    }
}
