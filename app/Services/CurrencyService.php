<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected const CACHE_KEY = 'currency_settings';
    protected const CACHE_TTL = 3600;

    public function getSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return [
                'code'               => Setting::get('currency_code', 'INR'),
                'symbol'             => Setting::get('currency_symbol', '₹'),
                'position'           => Setting::get('currency_symbol_position', 'before'),
                'decimal_places'     => (int) Setting::get('currency_decimal_places', '2'),
                'thousand_separator' => Setting::get('currency_thousand_separator', ','),
                'decimal_separator'  => Setting::get('currency_decimal_separator', '.'),
            ];
        });
    }

    public function format(float|int|string|null $amount): string
    {
        $settings = $this->getSettings();
        $amount = (float) ($amount ?? 0);

        $formatted = number_format(
            $amount,
            $settings['decimal_places'],
            $settings['decimal_separator'],
            $settings['thousand_separator']
        );

        if ($settings['position'] === 'after') {
            return $formatted . $settings['symbol'];
        }

        return $settings['symbol'] . $formatted;
    }

    public function getCode(): string
    {
        return $this->getSettings()['code'];
    }

    public function getSymbol(): string
    {
        return $this->getSettings()['symbol'];
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
