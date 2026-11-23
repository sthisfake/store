<?php

namespace App\Services;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class SessionCart
{
    private const SESSION_KEY = 'store_cart';

    public function add(Product $product,int $quantity): void {

        $quantities = $this->quantities();

        $newQuantity = ($quantities[$product->id] ?? 0) + $quantity;

        $this->validateProduct($product,$newQuantity);

        $quantities[$product->id] = $newQuantity;

        $this->save($quantities);
    }

    public function update(Product $product,int $quantity): void {

        $this->validateProduct($product,$quantity);

        $quantities = $this->quantities();
        $quantities[$product->id] = $quantity;

        $this->save($quantities);
    }

    public function remove(Product $product): void {
        $quantities = $this->quantities();

        unset($quantities[$product->id]);

        $this->save($quantities);
    }

    public function clear(): void {

        session()->forget(self::SESSION_KEY);
        
    }

    public function summary(): array {

        $quantities = $this->quantities();

        if ($quantities === []) {

            return [
                'items' => [],
                'count' => 0,
                'subtotal' => 0,
            ];

        }

        $products = Product::query()->with(['category:id,is_active','primaryImage:id,product_id,image_path',])
            ->whereIn('id', array_keys($quantities))->get()->keyBy('id');

        $items = [];
        $validQuantities = [];
        $count = 0;
        $subtotal = 0;

        foreach ($quantities as $productId => $quantity) {

            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $quantity = (int) $quantity;

            $unitPrice = $product->isOnSale() ? (float) $product->sale_price : (float) $product->price;

            $lineTotal = round($unitPrice * $quantity,2);

            $isAvailable = $product->status === ProductStatus::ACTIVE && $product->category?->is_active && (
                    ! $product->track_inventory
                    || $product->stock_quantity >= $quantity
                );

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'is_available' => $isAvailable,
                'maximum_quantity' => $product->track_inventory? max($product->stock_quantity, 1): 99
            ];

            $validQuantities[$product->id] = $quantity;
            $count += $quantity;
            $subtotal += $lineTotal;
        }

        $this->save($validQuantities);

        return [
            'items' => $items,
            'count' => $count,
            'subtotal' => round($subtotal, 2),
        ];
    }

    private function quantities(): array {
        return session()->get(self::SESSION_KEY,[]);
    }

    public function checkoutQuantities(): array
    {
        return $this->quantities();
    }

    private function save(array $quantities): void {
        session()->put(self::SESSION_KEY,$quantities);
    }

    private function validateProduct(Product $product,int $quantity ): void {

        if ($product->status !== ProductStatus::ACTIVE|| ! $product->category->is_active) {

            throw ValidationException::withMessages([
                'cart' => 'This product is not currently available.',
            ]);

        }

        if ($quantity < 1 || $quantity > 99) {

            throw ValidationException::withMessages([
                'cart' => 'Quantity must be between 1 and 99.',
            ]);

        }

        if ($product->track_inventory&& $quantity > $product->stock_quantity) {

            throw ValidationException::withMessages([
                'cart' => 'The requested quantity is not available.',
            ]);

        }
    }
}