<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with([
                'childrenCategory' => function ($query): void {
                    $query
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->with([
                            'childrenCategory' => function ($query): void {
                                $query
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->orderBy('name');
                            },
                        ]);
                },
            ])
            ->withCount([
                'products as active_products_count' => function ($query): void {
                    $query->where('status', ProductStatus::ACTIVE->value);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $heroProducts = Product::query()
            ->where('status', ProductStatus::ACTIVE->value)
            ->whereHas('category', function ($query): void {
                $query->where('is_active', true);
            })
            ->whereHas('primaryImage')
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image_path',
            ])
            ->latest()
            ->limit(5)
            ->get();

        $discountedProducts = Product::query()
            ->where('status', ProductStatus::ACTIVE->value)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->whereHas('category', function ($query): void {
                $query->where('is_active', true);
            })
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image_path',
            ])
            ->orderByRaw('(price - sale_price) / NULLIF(price, 0) DESC')
            ->limit(12)
            ->get();

        $products = Product::query()
            ->where('status', ProductStatus::ACTIVE->value)
            ->whereHas('category', function ($query): void {
                $query->where('is_active', true);
            })
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image_path',
            ])
            ->latest()
            ->limit(8)
            ->get();

        return view('home', [
            'categories' => $categories,
            'heroProducts' => $heroProducts,
            'discountedProducts' => $discountedProducts,
            'products' => $products,
        ]);
    }
}
