<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->order_number }} | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                    Order details
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    {{ $order->order_number }}
                </h1>
            </div>

            <span class="rounded-full bg-blue-100 px-4 py-2 text-sm font-bold text-blue-700">
                {{ ucfirst($order->status->value) }}
            </span>
        </div>

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_340px]">
            <div class="space-y-8">
                <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-4">
                        <h2 class="text-xl font-bold">Items</h2>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($order->orderItems as $item)
                            <div class="flex items-center justify-between gap-4 px-6 py-5">
                                <div>
                                    <p class="font-bold">{{ $item->product_name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $item->quantity }} ×
                                        {{ number_format((float) $item->unit_price, 2) }}
                                    </p>
                                </div>

                                <p class="font-black">
                                    {{ number_format((float) $item->total_price, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Delivery address
                    </h2>

                    <p class="mt-4 font-semibold">
                        {{ $order->address->recipient_name ?: auth()->user()->name }}
                    </p>

                    <p class="mt-2 leading-7 text-slate-600">
                        {{ $order->address->address }}
                    </p>

                    <p class="text-slate-600">
                        {{ $order->address->city }},
                        {{ $order->address->province }}
                    </p>

                    <p class="mt-1 text-slate-600">
                        {{ $order->address->phone }}
                    </p>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">
                        Order status
                    </h2>

                    <div class="mt-5 space-y-4">
                        @foreach (['processing', 'shipped', 'delivered'] as $status)
                            @php
                                $statuses = [
                                    'processing' => 1,
                                    'shipped' => 2,
                                    'delivered' => 3,
                                ];

                                $currentPosition = $statuses[$order->status->value] ?? 0;
                                $statusPosition = $statuses[$status];
                            @endphp

                            <div class="flex items-center gap-3">
                                <span @class([
                                    'size-3 rounded-full',
                                    'bg-emerald-500' => $statusPosition <= $currentPosition,
                                    'bg-slate-300' => $statusPosition > $currentPosition,
                                ])></span>

                                <span @class([
                                    'font-semibold',
                                    'text-slate-950' => $statusPosition <= $currentPosition,
                                    'text-slate-400' => $statusPosition > $currentPosition,
                                ])>
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Payment</h2>

                    <p class="mt-4 text-sm text-slate-500">
                        Status
                    </p>

                    <p class="mt-1 font-bold">
                        {{ ucfirst($order->payment->status->value) }}
                    </p>

                    <p class="mt-4 text-sm text-slate-500">
                        Transaction
                    </p>

                    <p class="mt-1 break-all text-sm font-semibold">
                        {{ $order->payment->transaction_id }}
                    </p>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Summary</h2>

                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span>{{ number_format((float) $order->subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Discount</span>
                            <span>-{{ number_format((float) $order->discount_amount, 2) }}</span>
                        </div>

                        <div class="flex justify-between border-t border-slate-200 pt-3 text-base font-black">
                            <span>Total</span>
                            <span>{{ number_format((float) $order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </main>

    @include('store.partials.footer')
</body>
</html>