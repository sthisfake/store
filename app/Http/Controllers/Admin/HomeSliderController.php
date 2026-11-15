<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


class HomeSliderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $sliderProducts = Product::query()->where('show_on_home_slider', true)->with([

                'category:id,name',
                'primaryImage:id,product_id,image_path',

            ])->orderBy('home_slider_order')->orderBy('name')->get();

        $availableProducts = Product::query()->where('show_on_home_slider', false)->where('status', ProductStatus::ACTIVE->value)->whereHas('category', function ($query): void {

                $query->where('is_active', true);

            })->whereHas('primaryImage')->with([

                'category:id,name',
                'primaryImage:id,product_id,image_path',

            ])->when($search !== '', function ($query) use ($search): void {

                $query->where(function ($query) use ($search): void {

                    $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");

                });
            })->orderBy('name')->paginate(12)->withQueryString();

        return view('admin.home-slider.index', [
            'sliderProducts' => $sliderProducts,
            'availableProducts' => $availableProducts,
            'search' => $search,
        ]);
    }

    public function store(Product $product): RedirectResponse
    {
        if ($product->status !== ProductStatus::ACTIVE) {
            return back()->withErrors([
                'slider' => 'only active products can be added.',
            ]);
        }

        if (! $product->category->is_active) {
            return back()->withErrors([
                'slider' => 'the product category is inactive.',
            ]);
        }

        if (! $product->primaryImage()->exists()) {
            return back()->withErrors([
                'slider' => 'the product must have a primary image.',
            ]);
        }

        $sliderProductCount = Product::query()
            ->where('show_on_home_slider', true)
            ->count();

        if ($sliderProductCount >= 5) {
            return back()->withErrors([
                'slider' => 'the homepage slider can contain a maximum of 5 products.',
            ]);
        }

        $sliderProductCount = Product::query()
            ->where('show_on_home_slider', true)
            ->count();

        if ($sliderProductCount >= 5) {
            return back()->withErrors([
                'slider' => 'The homepage slider can contain a maximum of 5 products.',
            ]);
        }

        $product->update([
            'show_on_home_slider' => true,
            'home_slider_order' => $sliderProductCount + 1,
        ]);

        return back()->with('success', 'Product added to the homepage slider.');
    }

    public function updateOrder(Request $request,Product $product,): RedirectResponse {

        if (! $product->show_on_home_slider) {
            abort(404);
        }

        $data = $request->validate([
            'home_slider_order' => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
        ]);

        $sliderProductCount = Product::query()->where('show_on_home_slider', true)->count();

        $newPosition = (int) $data['home_slider_order'];

        if ($newPosition > $sliderProductCount) {
            return back()->withErrors([
                'home_slider_order' => "The position must be between 1 and {$sliderProductCount}.",
            ]);
        }

        $oldPosition = $product->home_slider_order;

        DB::transaction(function () use ($product,$oldPosition,$newPosition,): void {

            if ($newPosition < $oldPosition) {
                Product::query()
                    ->where('show_on_home_slider', true)
                    ->whereBetween(
                        'home_slider_order',
                        [$newPosition, $oldPosition - 1],
                    )
                    ->increment('home_slider_order');
            }

            if ($newPosition > $oldPosition) {
                Product::query()
                    ->where('show_on_home_slider', true)
                    ->whereBetween(
                        'home_slider_order',
                        [$oldPosition + 1, $newPosition],
                    )
                    ->decrement('home_slider_order');
            }

            $product->update([
                'home_slider_order' => $newPosition,
            ]);
        });

        return back()->with('success', 'Slider order updated.');
    }

    public function destroy(Product $product): RedirectResponse {

        if (! $product->show_on_home_slider) {
            abort(404);
        }

        $deletedPosition = $product->home_slider_order;

        DB::transaction(function () use ($product,$deletedPosition,): void {
            
            $product->update([
                'show_on_home_slider' => false,
                'home_slider_order' => 0,
            ]);

            Product::query()
                ->where('show_on_home_slider', true)
                ->where('home_slider_order', '>', $deletedPosition)
                ->decrement('home_slider_order');
        });

        return back()->with('success', 'Product removed from the homepage slider.');
    }
}