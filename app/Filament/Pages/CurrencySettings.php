<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Services\CurrencyService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CurrencySettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Currency';

    protected static ?string $title = 'Currency Settings';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.currency-settings';

    public ?array $data = [];

    protected static array $currencyPresets = [
        'USD' => ['symbol' => '$',   'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'EUR' => ['symbol' => '€',   'position' => 'after',  'decimal_places' => 2, 'thousand_separator' => '.', 'decimal_separator' => ','],
        'GBP' => ['symbol' => '£',   'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'INR' => ['symbol' => '₹',   'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'JPY' => ['symbol' => '¥',   'position' => 'before', 'decimal_places' => 0, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'AUD' => ['symbol' => 'A$',  'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'CAD' => ['symbol' => 'C$',  'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'CHF' => ['symbol' => 'CHF', 'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => "'", 'decimal_separator' => '.'],
        'CNY' => ['symbol' => '¥',   'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'BRL' => ['symbol' => 'R$',  'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => '.', 'decimal_separator' => ','],
        'MXN' => ['symbol' => 'MX$', 'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'SGD' => ['symbol' => 'S$',  'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'AED' => ['symbol' => 'AED', 'position' => 'before', 'decimal_places' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.'],
        'KRW' => ['symbol' => '₩',   'position' => 'before', 'decimal_places' => 0, 'thousand_separator' => ',', 'decimal_separator' => '.'],
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'currency_code'               => Setting::get('currency_code', 'USD'),
            'currency_symbol'             => Setting::get('currency_symbol', '$'),
            'currency_symbol_position'    => Setting::get('currency_symbol_position', 'before'),
            'currency_decimal_places'     => Setting::get('currency_decimal_places', '2'),
            'currency_thousand_separator' => Setting::get('currency_thousand_separator', ','),
            'currency_decimal_separator'  => Setting::get('currency_decimal_separator', '.'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Currency Configuration')
                    ->description('Choose the default currency for your store. All prices will be displayed using these settings.')
                    ->schema([
                        Forms\Components\Select::make('currency_code')
                            ->label('Currency')
                            ->options(collect(static::$currencyPresets)->mapWithKeys(
                                fn ($preset, $code) => [$code => $code . ' (' . $preset['symbol'] . ')']
                            ))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state && isset(static::$currencyPresets[$state])) {
                                    $preset = static::$currencyPresets[$state];
                                    $set('currency_symbol', $preset['symbol']);
                                    $set('currency_symbol_position', $preset['position']);
                                    $set('currency_decimal_places', (string) $preset['decimal_places']);
                                    $set('currency_thousand_separator', $preset['thousand_separator']);
                                    $set('currency_decimal_separator', $preset['decimal_separator']);
                                }
                            }),

                        Forms\Components\TextInput::make('currency_symbol')
                            ->label('Symbol')
                            ->required()
                            ->maxLength(5),

                        Forms\Components\Select::make('currency_symbol_position')
                            ->label('Symbol Position')
                            ->options([
                                'before' => 'Before amount ($100.00)',
                                'after'  => 'After amount (100.00€)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('currency_decimal_places')
                            ->label('Decimal Places')
                            ->options([
                                '0' => '0 (e.g., 1,000)',
                                '2' => '2 (e.g., 1,000.00)',
                                '3' => '3 (e.g., 1,000.000)',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('currency_thousand_separator')
                            ->label('Thousand Separator')
                            ->maxLength(1)
                            ->placeholder(','),

                        Forms\Components\TextInput::make('currency_decimal_separator')
                            ->label('Decimal Separator')
                            ->maxLength(1)
                            ->placeholder('.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Preview')
                    ->description('This is how prices will appear across your store.')
                    ->schema([
                        Forms\Components\Placeholder::make('preview')
                            ->label('')
                            ->content(function (Forms\Get $get): string {
                                $symbol = $get('currency_symbol') ?? '$';
                                $position = $get('currency_symbol_position') ?? 'before';
                                $decimals = (int) ($get('currency_decimal_places') ?? 2);
                                $thousandSep = $get('currency_thousand_separator') ?? ',';
                                $decimalSep = $get('currency_decimal_separator') ?? '.';

                                $formatted = number_format(1234.56, $decimals, $decimalSep, $thousandSep);
                                $display = $position === 'after'
                                    ? $formatted . $symbol
                                    : $symbol . $formatted;

                                return "Sample: **{$display}**";
                            }),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        app(CurrencyService::class)->clearCache();

        Notification::make()
            ->title('Currency settings saved successfully')
            ->success()
            ->send();
    }
}
