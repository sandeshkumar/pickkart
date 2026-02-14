<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Revenue (Last 7 Days)';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($daysAgo) => Carbon::now()->subDays($daysAgo));

        $revenue = $days->map(function ($date) {
            return Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenue->values()->toArray(),
                    'fill' => true,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $days->map(fn ($date) => $date->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
