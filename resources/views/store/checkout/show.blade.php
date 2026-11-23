<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                Final step
            </p>

            <h1 class="mt-2 text-3xl font-black">
                Checkout
            </h1>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('checkout.pay') }}"
            class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_380px]"
        >
            @csrf

            <div class="space-y-8">
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold">
                                Delivery address
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Choose one of your saved addresses.
                            </p>
                        </div>

                        <a
                            href="{{ route('account.profile') }}#addresses"
                            class="text-sm font-bold text-orange-700"
                        >
                            Manage addresses
                        </a>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        @forelse ($addresses as $address)
                            <label class="cursor-pointer rounded-xl border border-slate-200 p-4 has-[:checked]:border-orange-500 has-[:checked]:ring-2 has-[:checked]:ring-orange-100">
                                <div class="flex items-start gap-3">
                                    <input
                                        type="radio"
                                        name="address_id"
                                        value="{{ $address->id }}"
                                        @checked(
                                            (int) old(
                                                'address_id',
                                                $addresses->firstWhere('is_default', true)?->id
                                                    ?? $addresses->first()?->id,
                                            ) === $address->id
                                        )
                                    >

                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold">
                                                {{ $address->title }}
                                            </p>

                                            @if ($address->is_default)
                                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">
                                                    Default
                                                </span>
                                            @endif
                                        </div>

                                        <p class="mt-2 text-sm font-semibold">
                                            {{ $address->recipient_name ?: auth()->user()->name }}
                                        </p>

                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                            {{ $address->address }}
                                        </p>

                                        <p class="text-sm text-slate-600">
                                            {{ $address->city }}, {{ $address->province }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $address->phone }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 p-6 text-slate-500 md:col-span-2">
                                Add an address before completing checkout.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Order notes
                    </h2>

                    <textarea
                        name="notes"
                        rows="4"
                        maxlength="2000"
                        placeholder="Optional notes for your order"
                        class="mt-4 w-full rounded-lg border border-slate-300 px-3 py-2.5"
                    >{{ old('notes') }}</textarea>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Payment
                    </h2>

                    <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="font-bold text-emerald-800">
                            Mock payment
                        </p>

                        <p class="mt-1 text-sm text-emerald-700">
                            Clicking Pay will immediately complete this payment.
                        </p>
                    </div>
                </section>
            </div>

            <aside>
                <div class="sticky top-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Order summary
                    </h2>

                    <div class="mt-5 max-h-96 space-y-4 overflow-y-auto">
                        @foreach ($cartSummary['items'] as $item)
                            <div class="flex gap-3 border-b border-slate-100 pb-4">
                                <div class="size-16 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                    @if ($item['product']->primaryImage)
                                        <img
                                            src="{{ asset('storage/'.$item['product']->primaryImage->image_path) }}"
                                            alt="{{ $item['product']->name }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-sm font-bold">
                                        {{ $item['product']->name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Quantity: {{ $item['quantity'] }}
                                    </p>

                                    @if (! $item['is_available'])
                                        <p class="mt-1 text-xs font-bold text-red-600">
                                            Unavailable
                                        </p>
                                    @endif
                                </div>

                                <p class="text-sm font-bold">
                                    {{ number_format($item['line_total'], 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-slate-200 pt-5">
                        <span class="font-semibold">Total</span>

                        <span class="text-2xl font-black">
                            {{ number_format($cartSummary['subtotal'], 2) }}
                        </span>
                    </div>

                    <button
                        type="submit"
                        @disabled(
                            ! $cartReady
                            || $addresses->isEmpty()
                        )
                        @class([
                            'mt-6 w-full rounded-xl px-5 py-4 font-bold text-white',
                            'bg-slate-950 hover:bg-orange-600' => $cartReady && $addresses->isNotEmpty(),
                            'cursor-not-allowed bg-slate-300' => ! $cartReady || $addresses->isEmpty(),
                        ])
                    >
                        Pay now
                    </button>
                </div>
            </aside>
        </form>
    </main>

    @include('store.partials.footer')
</body>
</html>