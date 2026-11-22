<div
    data-cart-overlay
    class="fixed inset-0 z-[80] hidden bg-black/45 backdrop-blur-sm"
></div>

<aside
    data-cart-drawer
    data-open="{{ session('open_cart') ? 'true' : 'false' }}"
    aria-hidden="true"
    class="fixed right-0 top-0 z-[90] flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300"
>
    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
        <div>
            <h2 class="text-xl font-black">Your cart</h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $headerCart['count'] }}
                {{ Str::plural('item', $headerCart['count']) }}
            </p>
        </div>

        <button
            type="button"
            data-cart-close
            aria-label="Close cart"
            class="grid size-10 place-items-center rounded-full border border-slate-300 text-xl hover:bg-slate-100"
        >
            &times;
        </button>
    </div>

    @if (session('cart_success'))
        <div class="mx-5 mt-4 rounded-lg bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
            {{ session('cart_success') }}
        </div>
    @endif

    @if ($errors->has('cart') || $errors->has('quantity'))
        <div class="mx-5 mt-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
            {{ $errors->first('cart') ?: $errors->first('quantity') }}
        </div>
    @endif

    <div class="flex-1 overflow-y-auto px-5 py-5">
        <div class="space-y-5">
            @forelse ($headerCart['items'] as $item)
                <article class="border-b border-slate-200 pb-5">
                    <div class="flex gap-4">
                        <a
                            href="{{ route('products.show', ['product' => $item['product']->slug]) }}"
                            class="size-20 shrink-0 overflow-hidden rounded-lg bg-slate-100"
                        >
                            @if ($item['product']->primaryImage)
                                <img
                                    src="{{ asset('storage/'.$item['product']->primaryImage->image_path) }}"
                                    alt="{{ $item['product']->name }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div class="grid h-full place-items-center text-xs text-slate-400">
                                    No image
                                </div>
                            @endif
                        </a>

                        <div class="min-w-0 flex-1">
                            <a
                                href="{{ route('products.show', ['product' => $item['product']->slug]) }}"
                                class="line-clamp-2 font-bold hover:text-orange-700"
                            >
                                {{ $item['product']->name }}
                            </a>

                            <p class="mt-2 text-sm font-semibold">
                                {{ number_format($item['unit_price'], 2) }}
                            </p>

                            @if (! $item['is_available'])
                                <p class="mt-2 text-xs font-semibold text-red-600">
                                    Requested quantity is unavailable.
                                </p>
                            @endif
                        </div>

                        <form
                            method="POST"
                            action="{{ route('cart.items.destroy', ['product' => $item['product']->slug]) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                aria-label="Remove product"
                                class="text-sm font-semibold text-red-600"
                            >
                                Remove
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 flex items-end justify-between gap-4">
                        <form
                            method="POST"
                            action="{{ route('cart.items.update', ['product' => $item['product']->slug]) }}"
                            data-cart-quantity-form
                            class="flex items-center overflow-hidden rounded-lg border border-slate-300"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="button"
                                data-cart-decrease
                                aria-label="Decrease quantity"
                                class="grid size-10 place-items-center text-lg font-bold hover:bg-slate-100"
                            >
                                -
                            </button>

                            <input
                                type="number"
                                name="quantity"
                                value="{{ $item['quantity'] }}"
                                min="1"
                                max="{{ $item['maximum_quantity'] }}"
                                data-cart-quantity
                                class="h-10 w-14 border-x border-slate-300 text-center outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                            >

                            <button
                                type="button"
                                data-cart-increase
                                aria-label="Increase quantity"
                                class="grid size-10 place-items-center text-lg font-bold hover:bg-slate-100"
                            >
                                +
                            </button>
                        </form>

                        <p class="font-black">
                            {{ number_format($item['line_total'], 2) }}
                        </p>
                    </div>
                </article>
            @empty
                <div class="py-16 text-center">
                    <p class="text-lg font-bold">Your cart is empty</p>

                    <p class="mt-2 text-sm text-slate-500">
                        Add a product to see it here.
                    </p>

                    <button
                        type="button"
                        data-cart-close
                        class="mt-5 rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white"
                    >
                        Continue shopping
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    @if ($headerCart['items'] !== [])
        <div class="border-t border-slate-200 bg-slate-50 p-5">
            <div class="flex items-center justify-between">
                <span class="font-semibold">Subtotal</span>

                <span class="text-xl font-black">
                    {{ number_format($headerCart['subtotal'], 2) }}
                </span>
            </div>

            <p class="mt-2 text-xs text-slate-500">
                Shipping and taxes will be calculated during checkout.
            </p>

            <form
                method="POST"
                action="{{ route('cart.clear') }}"
                class="mt-4"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="w-full rounded-lg border border-red-300 px-4 py-3 text-sm font-bold text-red-600"
                >
                    Clear cart
                </button>
            </form>
        </div>
    @endif
</aside>