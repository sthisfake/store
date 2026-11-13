<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $product = Product::query()->whereKey($product->id)->where('status', ProductStatus::ACTIVE->value)->whereHas('category', function ($query): void {

                $query->where('is_active', true);

        })->firstOrFail();

        $product->load(['category.parentCategory','productImages' => function ($query): void {
                $query->orderByDesc('is_primary')->orderBy('sort_order');
            },
        ]);

        $approvedReviewsQuery = $product->reviews()->where('is_approved', true);

        $reviewCount = (clone $approvedReviewsQuery)->count();

        $averageRating = (float) ((clone $approvedReviewsQuery)->avg('rating') ?? 0);

        $reviews = $approvedReviewsQuery->with('user:id,name')->latest()->paginate(5)->withQueryString();

        $relatedProducts = Product::query()->where('status', ProductStatus::ACTIVE->value)->where('category_id', $product->category_id)->whereKeyNot($product->id)->with([
                'category:id,name',
                'primaryImage:id,product_id,image_path',
            ])->latest()->limit(4)->get();

        $categories = Category::query()->where('is_active', true)->whereNull('parent_id')->with(['childrenCategory' => function ($query): void {

            $query->where('is_active', true)->orderBy('sort_order')->orderBy('name')->with([

                'childrenCategory' => function ($query): void {

                    $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');

                        },
                    ]);
                },
            ])->orderBy('sort_order')->orderBy('name')->get();

        $primaryImage = $product->productImages->firstWhere('is_primary', true)?? $product->productImages->first();

        return view('store.products.show', [
            'product' => $product,
            'primaryImage' => $primaryImage,
            'reviews' => $reviews,
            'reviewCount' => $reviewCount,
            'averageRating' => $averageRating,
            'relatedProducts' => $relatedProducts,
            'categories' => $categories,
        ]);
    }
}