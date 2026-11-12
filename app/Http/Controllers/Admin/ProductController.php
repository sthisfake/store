<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\models\Category;
use App\models\ProductImage;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;







class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with([
                'category',
                'primaryImage',
            ])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.create', [
            'categories' => $categories,
            'statuses' => ProductStatus::cases(),
            'productTypes' => ProductType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'track_inventory' => ['required', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $images = $request->file('images', []);

        unset($data['images']);

        $data['track_inventory'] = $request->boolean('track_inventory');

        if (! $data['track_inventory']) {
            $data['stock_quantity'] = 0;
        }

        DB::transaction(function () use ($data, $images): void {
            $product = Product::query()->create($data);

            $this->storeImages($product, $images);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'productImages' => function ($query): void {
                $query->orderBy('sort_order');
            },
        ]);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $categories,
            'statuses' => ProductStatus::cases(),
            'productTypes' => ProductType::cases(),
        ]);
    }

    public function update(Request $request,Product $product): RedirectResponse {

        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($product->id),
            ],
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::enum(ProductStatus::class)],
            'product_type' => ['required', Rule::enum(ProductType::class)],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'track_inventory' => ['required', 'boolean'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $images = $request->file('images', []);

        if ($product->productImages()->count() + count($images) > 8) {
            return back()
                ->withErrors([
                    'images' => 'A product can have a maximum of 8 images.',
                ])
                ->withInput();
        }

        unset($data['images']);

        $data['track_inventory'] = $request->boolean('track_inventory');

        if (! $data['track_inventory']) {
            $data['stock_quantity'] = 0;
        }

        DB::transaction(function () use ($product, $data, $images): void {
            $product->update($data);

            $this->storeImages($product, $images);
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function setPrimaryImage(Product $product,ProductImage $image): RedirectResponse {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        DB::transaction(function () use ($product, $image): void {
            $product->productImages()->update([
                'is_primary' => false,
            ]);

            $image->update([
                'is_primary' => true,
            ]);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'primery image changed successfully.');
    }

    public function destroyImage(Product $product,ProductImage $image): RedirectResponse {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        $imagePath = $image->image_path;
        $wasPrimary = $image->is_primary;

        DB::transaction(function () use ($product, $image, $wasPrimary): void {
            $image->delete();

            if ($wasPrimary) {
                $nextImage = $product->productImages()
                    ->orderBy('sort_order')
                    ->first();

                $nextImage?->update([
                    'is_primary' => true,
                ]);
            }
        });

        Storage::disk('public')->delete($imagePath);

        return back()->with('success', 'Product image deleted.');
    }

    private function storeImages(Product $product, array $images): void
    {
        $hasPrimaryImage = $product->productImages()
            ->where('is_primary', true)
            ->exists();

        $lastSortOrder = $product->productImages()
            ->max('sort_order');

        $nextSortOrder = $lastSortOrder === null
            ? 0
            : $lastSortOrder + 1;

        foreach ($images as $image) {
            $imagePath = $image->store('products', 'public');

            $product->productImages()->create([
                'image_path' => $imagePath,
                'is_primary' => ! $hasPrimaryImage,
                'sort_order' => $nextSortOrder,
            ]);

            $hasPrimaryImage = true;
            $nextSortOrder++;
        }
    }

}
