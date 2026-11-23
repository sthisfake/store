<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                    My account
                </p>

                <h1 class="mt-2 text-3xl font-black">
                    My orders
                </h1>
            </div>

            <a
                href="{{ route('account.profile') }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-bold"
            >
                Profile
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($orders as $order)
                <a
                    href="{{ route('account.orders.show', $order) }}"
                    class="grid gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-orange-300 sm:grid-cols-4 sm:items-center"
                >
                    <div>
                        <p class="text-xs text-slate-500">Order</p>
                        <p class="mt-1 font-bold">{{ $order->order_number }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Date</p>
                        <p class="mt-1 font-semibold">
                            {{ $order->created_at->format('M j, Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">Status</p>
                        <p class="mt-1 font-semibold">
                            {{ ucfirst($order->status->value) }}
                        </p>
                    </div>

                    <div class="sm:text-right">
                        <p class="text-xs text-slate-500">Total</p>
                        <p class="mt-1 text-lg font-black">
                            {{ number_format((float) $order->total_amount, 2) }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
                    You have not placed an order yet.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </main>

    @include('store.partials.footer')
</body>
</html>