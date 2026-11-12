<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = OrderStatus::tryFrom((string) $request->query('status'));

        $orders = Order::query()->with(['user:id,name,email','payment:id,order_id,status'])->when($search !== '', function ($query) use ($search): void {

            $query->where(function ($query) use ($search): void {

                $query->where('order_number', 'like', "%{$search}%")->orWhereHas('user', function ($query) use ($search): void {

                    $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                });
            });

        })->when($status !== null, function ($query) use ($status): void {

                $query->where('status', $status->value);
                
            })->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
        ]);
    }

    public function show(Order $order): View
    {
        $order->load([
            'user',
            'address',
            'orderItems.product',
            'payment',
        ]);

        return view('admin.orders.show', [
            'order' => $order,
            'allowedStatuses' => $this->allowedTransitions($order->status),
        ]);
    }

    public function updateStatus(Request $request,Order $order): RedirectResponse
     {
        
        $data = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ]);

        $newStatus = OrderStatus::from($data['status']);
        $allowedStatuses = $this->allowedTransitions($order->status);

        if (! in_array($newStatus, $allowedStatuses, true)) {
            return back()->withErrors([
                'status' => 'This status transition is not allowed.',
            ]);
        }

        if ($newStatus === OrderStatus::REFUNDED) {
            $order->loadMissing('payment');

            if ($order->payment?->status !== PaymentStatus::REFUNDED) {
                return back()->withErrors([
                    'status' => 'The payment must be refunded before the order can be marked as refunded.',
                ]);
            }
        }

        $changes = [
            'status' => $newStatus,
        ];

        if ($newStatus === OrderStatus::CANCELLED) {
            $changes['cancelled_at'] = now();
        }

        $order->update($changes);

        return back()->with('success', 'Order status updated successfully.');
    }

    private function allowedTransitions(OrderStatus $status): array
    {
        switch ($status) {
            case OrderStatus::PENDING:
                return [
                    OrderStatus::PROCESSING,
                    OrderStatus::CANCELLED,
                ];

            case OrderStatus::PROCESSING:
                return [
                    OrderStatus::SHIPPED,
                    OrderStatus::CANCELLED,
                ];

            case OrderStatus::SHIPPED:
                return [
                    OrderStatus::DELIVERED,
                ];

            case OrderStatus::DELIVERED:
                return [
                    OrderStatus::REFUNDED,
                ];

            case OrderStatus::CANCELLED:
            case OrderStatus::REFUNDED:
                return [];

            default:
                return [];
        }
    }
}