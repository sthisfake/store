<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->with('payment:id,order_id,status')->latest()->paginate(10);

        return view('store.account.orders.index', [
            'orders' => $orders,
            'categories' => $this->navigationCategories(),
        ]);
    }

    public function show(Request $request,Order $order,): View {

        $order = $request->user()->orders()->whereKey($order->id)->with([
                'orderItems',
                'address',
                'payment',
            ])->firstOrFail();

        return view('store.account.orders.show', [
            'order' => $order,
            'categories' => $this->navigationCategories(),
        ]);
    }

    private function navigationCategories()
    {
        return Category::query()->where('is_active', true)->whereNull('parent_id')->with([

                'childrenCategory' => function ($query): void {
                    $query->where('is_active', true)->orderBy('sort_order')->orderBy('name')->with([

                            'childrenCategory' => function ($query): void {
                                $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                            },
                        ]);
                },
            ])->orderBy('sort_order')->orderBy('name')->get();
    }
}