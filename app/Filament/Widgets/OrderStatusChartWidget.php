<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Orders by Status';

    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'refunded'];

        $counts = collect($statuses)->map(fn ($status) => Order::where('status', $status)->count());

        $colors = [
            'rgba(245, 158, 11, 0.8)',  // pending - amber
            'rgba(59, 130, 246, 0.8)',   // processing - blue
            'rgba(99, 102, 241, 0.8)',   // shipped - indigo
            'rgba(34, 197, 94, 0.8)',    // delivered - green
            'rgba(239, 68, 68, 0.8)',    // cancelled - red
            'rgba(156, 163, 175, 0.8)',  // returned - gray
            'rgba(107, 114, 128, 0.8)',  // refunded - dark gray
        ];

        return [
            'datasets' => [
                [
                    'data' => $counts->toArray(),
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => collect($statuses)->map(fn ($s) => ucfirst($s))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
