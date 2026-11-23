<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function pay(User $user, int $addressId, ?string $notes, SessionCart $cart): Order {

        $address = $user->addresses()->whereKey($addressId)->first();

        if (! $address) {
            throw ValidationException::withMessages([
                'checkout' => 'The selected address is invalid.',
            ]);
        }

        $quantities = $cart->checkoutQuantities();

        if ($quantities === []) {
            throw ValidationException::withMessages([
                'checkout' => 'Your cart is empty.',
            ]);
        }

        $order = DB::transaction(function () use ($user,$address,$notes,$quantities): Order {

            // get the products from the card id's with their category id and active status of category , and locking th rows  
            $products = Product::query()->with('category:id,is_active')->whereIn('id', array_keys($quantities))->lockForUpdate()->get()->keyBy('id');

            if ($products->count() !== count($quantities)) {
                throw ValidationException::withMessages([
                    'checkout' => 'One or more cart products are no longer available.',
                ]);
            }

            $items = [];
            $subtotal = 0;
            $discountAmount = 0;
            $totalAmount = 0;

            foreach ($quantities as $productId => $quantity) {

                $product = $products->get($productId);
                $quantity = (int) $quantity;

                if ($product->status !== ProductStatus::ACTIVE|| ! $product->category->is_active) {
                    throw ValidationException::withMessages([
                        'checkout' => "{$product->name} is no longer available.",
                    ]);
                }

                if ($quantity < 1 || $quantity > 99) {
                    throw ValidationException::withMessages([
                        'checkout' => "The quantity for {$product->name} is invalid.",
                    ]);
                }

                if ($product->track_inventory&& $quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'checkout' => "There is not enough stock for {$product->name}.",
                    ]);
                }

                $regularUnitPrice = (float) $product->price;

                $unitPrice = $product->isOnSale() ? (float) $product->sale_price : $regularUnitPrice;

                $regularLineTotal = round($regularUnitPrice * $quantity,2);

                $lineTotal = round($unitPrice * $quantity,2);

                $subtotal += $regularLineTotal;
                $discountAmount += $regularLineTotal - $lineTotal;
                $totalAmount += $lineTotal;

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            $subtotal = round($subtotal, 2);
            $discountAmount = round($discountAmount, 2);
            $totalAmount = round($totalAmount, 2);

            $order = $user->orders()->create([
                'address_id' => $address->id,
                'order_number' => $this->orderNumber(),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_cost' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'status' => OrderStatus::PROCESSING->value,
                'notes' => $notes,
                'paid_at' => now(),
            ]);

            foreach ($items as $item) {

                $order->orderItems()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                if ($item['product']->track_inventory) {
                    $item['product']->decrement('stock_quantity',$item['quantity']);
                }
            }

            // fake payment
            $order->payment()->create([
                'transaction_id' => 'MOCK-'.Str::upper(Str::uuid()->toString()),
                'gateway' => 'mock',
                'amount' => $totalAmount,
                'status' => PaymentStatus::COMPLETED->value,
                'paid_at' => now(),
            ]);

            return $order;
        });

        $cart->clear();

        return $order->load([
            'orderItems',
            'payment',
            'address',
        ]);
    }

    private function orderNumber(): string
    {
        $orderNumber = 'KAKA-'. now()->format('Ymd'). '-'. Str::upper(Str::random(8));

        return $orderNumber;
    }
}