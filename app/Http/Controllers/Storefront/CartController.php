<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\SessionCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function store(Request $request,Product $product,SessionCart $cart): RedirectResponse {

        $data = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        try {

            $cart->add($product,$data['quantity']);

        } catch (ValidationException $exception) {

            return back()->withErrors($exception->errors())->withInput()->with('open_cart', true);

        }

        return back()->with('cart_success', 'Product added to cart.')->with('open_cart', true);
    }

    public function update(Request $request,Product $product,SessionCart $cart): RedirectResponse {

        $data = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],
        ]);

        try {

            $cart->update($product,$data['quantity']);

        } catch (ValidationException $exception) {

            return back()->withErrors($exception->errors())->withInput()->with('open_cart', true);

        }

        return back()->with('cart_success', 'Cart updated.')->with('open_cart', true);
    }

    public function destroy(Product $product,SessionCart $cart): RedirectResponse {

        $cart->remove($product);

        return back()->with('cart_success', 'Product removed from cart.')->with('open_cart', true);
    }

    public function clear(SessionCart $cart): RedirectResponse {

        $cart->clear();

        return back()->with('cart_success', 'Cart cleared.')->with('open_cart', true);
    }
}