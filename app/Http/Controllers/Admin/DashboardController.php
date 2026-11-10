<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statistics = [
            'Products' => Product::query()->count(),
            'Categories' => Category::query()->count(),
            'Orders' => Order::query()->count(),
            'Customers' => User::query()
                ->where('role', 'customer')
                ->count(),
        ];

        $recentOrders = Order::query()
            ->with('user:id,name')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'statistics',
            'recentOrders',
        ));
    }
}