<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CheckoutService;
use App\Services\SessionCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(Request $request,SessionCart $cart): View|RedirectResponse {

        $cartSummary = $cart->summary();

        if ($cartSummary['items'] === []) {
            return redirect()->route('home')->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        $addresses = $request->user()->addresses()->orderByDesc('is_default')->latest()->get();

        $cartReady = true;

        foreach ($cartSummary['items'] as $item) {
            if (! $item['is_available']) {
                $cartReady = false;
                break;
            }
        }

        return view('store.checkout.show', [
            'cartSummary' => $cartSummary,
            'cartReady' => $cartReady,
            'addresses' => $addresses,
            'categories' => $this->navigationCategories(),
        ]);
    }

    public function pay(Request $request,SessionCart $cart,CheckoutService $checkout): RedirectResponse {

        $data = $request->validate([
            'address_id' => [
                'required',
                'integer',
                'exists:addresses,id',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $order = $checkout->pay($request->user(),$data['address_id'],$data['notes'] ?? null,$cart);

        return redirect()->route('account.orders.show', $order)->with(
                'success',
                'Payment completed and your order was created.',
            );
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