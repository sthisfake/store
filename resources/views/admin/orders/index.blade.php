@extends('admin.layouts.app')

@section('title', 'Orders')
@section('heading', 'Orders')

@section('content')
    <form
        method="GET"
        action="{{ route('admin.orders.index') }}"
        class="mb-6 grid gap-3 rounded-xl bg-white p-4 shadow-sm sm:grid-cols-[1fr_180px_auto]"
    >
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Order number, customer or email"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >

        <select
            name="status"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >
            <option value="">All statuses</option>

            @foreach ($statuses as $status)
                <option
                    value="{{ $status->value }}"
                    @selected(request('status') === $status->value)
                >
                    {{ ucfirst($status->value) }}
                </option>
            @endforeach
        </select>

        <div class="flex gap-2">
            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.orders.index') }}"
                class="rounded-lg border px-4 py-2 text-sm font-semibold"
            >
                Clear
            </a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="border-b bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-4">Order</th>
                    <th class="px-5 py-4">Customer</th>
                    <th class="px-5 py-4">Total</th>
                    <th class="px-5 py-4">Payment</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Created</th>
                    <th class="px-5 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-5 py-4 font-semibold">
                            {{ $order->order_number }}
                        </td>

                        <td class="px-5 py-4">
                            <p>{{ $order->user->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $order->user->email }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            {{ number_format((float) $order->total_amount, 2) }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $order->payment ? ucfirst($order->payment->status->value) : 'No payment' }}
                        </td>

                        <td class="px-5 py-4">
                            <span @class([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-amber-100 text-amber-700' => $order->status->value === 'pending',
                                'bg-blue-100 text-blue-700' => $order->status->value === 'processing',
                                'bg-purple-100 text-purple-700' => $order->status->value === 'shipped',
                                'bg-emerald-100 text-emerald-700' => $order->status->value === 'delivered',
                                'bg-red-100 text-red-700' => $order->status->value === 'cancelled',
                                'bg-slate-200 text-slate-700' => $order->status->value === 'refunded',
                            ])>
                                {{ ucfirst($order->status->value) }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            {{ $order->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="rounded border px-3 py-1.5"
                            >
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endsection