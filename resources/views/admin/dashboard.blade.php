@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($statistics as $label => $value)
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    {{ $label }}
                </p>

                <p class="mt-3 text-3xl font-bold">
                    {{ number_format($value) }}
                </p>
            </section>
        @endforeach
    </div>

    <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-bold">Recent Orders</h2>
            <p class="mt-1 text-sm text-slate-500">
                The five most recently created orders.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Created</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 font-semibold">
                                {{ $order->order_number }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $order->user->name }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">
                                    {{ ucfirst(str_replace('_', ' ', $order->status->value)) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                {{ number_format((float) $order->total_amount, 2) }}
                            </td>

                            <td class="px-6 py-4 text-slate-500">
                                {{ $order->created_at->format('M j, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                No orders have been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection