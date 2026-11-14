<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request,Category $category): View|RedirectResponse {
        if (! $category->is_active) {
            abort(404);
        }

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['integer'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'availability' => ['nullable', 'in:all,in_stock,out_of_stock'],
            'sale' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'in:newest,price_low,price_high,discount,name'],
        ]);

        $minimumPrice = isset($filters['min_price'])? (float) $filters['min_price']: null;
        $maximumPrice = isset($filters['max_price'])? (float) $filters['max_price']: null;

        if ($minimumPrice !== null&& $maximumPrice !== null&& $minimumPrice > $maximumPrice) {
            return back()->withErrors(['max_price' => 'Maximum price must be greater than minimum price.'])->withInput();
        }

        $allCategories = Category::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        $categoriesByParent = $allCategories->groupBy('parent_id');

        $categoryIds = $this->collectCategoryIds($category->id,$categoriesByParent);

        $filterCategories = $allCategories->whereIn('id', $categoryIds)->where('id', '!=', $category->id)->sortBy('name')->values();

        $requestedCategoryIds = array_map('intval',$filters['categories'] ?? []);

        $selectedCategoryIds = array_values(array_intersect($requestedCategoryIds,$filterCategories->pluck('id')->all()));

        $productCategoryIds = $categoryIds;

        if ($selectedCategoryIds !== []) {
            $productCategoryIds = [];

            foreach ($selectedCategoryIds as $selectedCategoryId) {
                
                $productCategoryIds = array_merge($productCategoryIds,$this->collectCategoryIds($selectedCategoryId,$categoriesByParent));
            
            }

            $productCategoryIds = array_values(array_unique($productCategoryIds));
        }

        $search = trim($filters['search'] ?? '');
        $availability = $filters['availability'] ?? 'all';
        $saleOnly = $request->boolean('sale');
        $sort = $filters['sort'] ?? 'newest';

        $productsQuery = Product::query()->where('status', ProductStatus::ACTIVE->value)->whereIn('category_id', $productCategoryIds)->with([
                'category:id,name,slug',
                'primaryImage:id,product_id,image_path',
            ]);

        if ($search !== '') {
            $productsQuery->where(function ($query) use ($search): void {
                $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($minimumPrice !== null) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) >= ?',[$minimumPrice]);
        }

        if ($maximumPrice !== null) {
            $productsQuery->whereRaw('COALESCE(sale_price, price) <= ?',[$maximumPrice]);
        }

        if ($availability === 'in_stock') {
            $productsQuery->where(function ($query): void {
                $query->where('track_inventory', false)->orWhere('stock_quantity', '>', 0);
            });
        }

        if ($availability === 'out_of_stock') {
            $productsQuery->where('track_inventory', true)->where('stock_quantity', 0);
        }

        if ($saleOnly) {
            $productsQuery->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
        }

        match ($sort) {

            'price_low' => $productsQuery
                ->orderByRaw('COALESCE(sale_price, price) ASC'),

            'price_high' => $productsQuery
                ->orderByRaw('COALESCE(sale_price, price) DESC'),

            'discount' => $productsQuery
                ->orderByRaw(
                    'CASE
                        WHEN sale_price IS NOT NULL AND price > 0
                        THEN (price - sale_price) / price
                        ELSE 0
                    END DESC'
                ),

            'name' => $productsQuery->orderBy('name'),

            default => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate(12)->withQueryString();

        $categories = Category::query()->where('is_active', true)->whereNull('parent_id')->with(['childrenCategory' => function ($query): void {

            $query->where('is_active', true)->orderBy('sort_order')->orderBy('name')->with(['childrenCategory' => function ($query): void {
                                
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                    
                    },
                ]);
            },
        ])->orderBy('sort_order')->orderBy('name')->get();

        return view('store.categories.show', [
            'category' => $category,
            'categories' => $categories,
            'filterCategories' => $filterCategories,
            'selectedCategoryIds' => $selectedCategoryIds,
            'products' => $products,
            'search' => $search,
            'minimumPrice' => $minimumPrice,
            'maximumPrice' => $maximumPrice,
            'availability' => $availability,
            'saleOnly' => $saleOnly,
            'sort' => $sort,
        ]);
    }

    private function collectCategoryIds(int $categoryId,Collection $categoriesByParent): array {
        
        $ids = [$categoryId];

        foreach ( $categoriesByParent->get($categoryId, collect()) as $childCategory) {
            $ids = array_merge($ids,$this->collectCategoryIds($childCategory->id,$categoriesByParent));
        }

        return $ids;
    }
}