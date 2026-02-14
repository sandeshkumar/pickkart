<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::role('customer')->count();

        $monthStart = Carbon::now()->startOfMonth();
        $ordersThisMonth = Order::where('created_at', '>=', $monthStart)->count();
        $revenueThisMonth = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $monthStart)
            ->sum('total');
        $newCustomersThisMonth = User::role('customer')
            ->where('created_at', '>=', $monthStart)
            ->count();
        $newProductsThisMonth = Product::where('created_at', '>=', $monthStart)->count();

        return [
            Stat::make('Total Revenue', format_currency($totalRevenue))
                ->description(format_currency($revenueThisMonth) . ' this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Total Orders', number_format($totalOrders))
                ->description($ordersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->chart([3, 5, 2, 7, 4, 6, 5]),

            Stat::make('Total Products', number_format($totalProducts))
                ->description($newProductsThisMonth . ' added this month')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning')
                ->chart([5, 4, 6, 3, 7, 5, 4]),

            Stat::make('Customers', number_format($totalCustomers))
                ->description($newCustomersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([4, 6, 3, 5, 7, 4, 6]),
        ];
    }
}
