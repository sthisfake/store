@extends('admin.layouts.app')

@section('title', 'Order '.$order->order_number)
@section('heading', 'Order '.$order->order_number)

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a
            href="{{ route('admin.orders.index') }}"
            class="rounded-lg border bg-white px-4 py-2 text-sm font-semibold"
        >
            Back
        </a>

        @if (count($allowedStatuses) > 0)
            <form
                method="POST"
                action="{{ route('admin.orders.status', $order) }}"
                class="flex gap-2"
            >
                @csrf
                @method('PATCH')

                <select
                    name="status"
                    required
                    class="rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
                    <option value="">Select next status</option>

                    @foreach ($allowedStatuses as $status)
                        <option value="{{ $status->value }}">
                            {{ ucfirst($status->value) }}
                        </option>
                    @endforeach
                </select>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                >
                    Update status
                </button>
            </form>
        @else
            <span class="rounded-full bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">
                Final status
            </span>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="overflow-hidden rounded-xl bg-white shadow-sm">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold">Items</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-5 py-4">Product</th>
                                <th class="px-5 py-4">Price</th>
                                <th class="px-5 py-4">Quantity</th>
                                <th class="px-5 py-4 text-right">Total</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($order->orderItems as $item)
                                <tr>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold">{{ $item->product_name }}</p>

                                        @if ($item->product)
                                            <p class="text-xs text-slate-500">
                                                {{ $item->product->sku ?? $item->product->slug }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ number_format((float) $item->unit_price, 2) }}
                                    </td>

                                    <td class="px-5 py-4">
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        {{ number_format((float) $item->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold">Shipping Address</h2>

                <p class="font-semibold">{{ $order->address->recipient_name }}</p>
                <p class="mt-2 text-sm text-slate-600">
                    {{ $order->address->province }}, {{ $order->address->city }}
                </p>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $order->address->address }}
                </p>
                <p class="mt-1 text-sm text-slate-600">
                    Postal code: {{ $order->address->postal_code }}
                </p>
                <p class="mt-1 text-sm text-slate-600">
                    Phone: {{ $order->address->phone }}
                </p>
            </section>

            @if ($order->notes)
                <section class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold">Order Notes</h2>
                    <p class="whitespace-pre-line text-sm text-slate-600">
                        {{ $order->notes }}
                    </p>
                </section>
            @endif
        </div>

        <div class="space-y-6">
            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold">Customer</h2>

                <p class="font-semibold">{{ $order->user->name }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $order->user->email }}</p>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $order->user->phone ?? 'No phone number' }}
                </p>

                <a
                    href="{{ route('admin.customers.show', $order->user) }}"
                    class="mt-4 inline-block text-sm font-semibold text-blue-600"
                >
                    View customer
                </a>
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold">Payment</h2>

                @if ($order->payment)
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="font-semibold">
                                {{ ucfirst($order->payment->status->value) }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Gateway</dt>
                            <dd>{{ $order->payment->gateway }}</dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Transaction</dt>
                            <dd>{{ $order->payment->transaction_id ?? 'Not available' }}</dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-slate-500">Amount</dt>
                            <dd>
                                {{ number_format((float) $order->payment->amount, 2) }}
                            </dd>
                        </div>
                    </dl>
                @else
                    <p class="text-sm text-slate-500">No payment record.</p>
                @endif
            </section>

            <section class="rounded-xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold">Summary</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Subtotal</dt>
                        <dd>{{ number_format((float) $order->subtotal, 2) }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-slate-500">Discount</dt>
                        <dd>{{ number_format((float) $order->discount_amount, 2) }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-slate-500">Shipping</dt>
                        <dd>{{ number_format((float) $order->shipping_cost, 2) }}</dd>
                    </div>

                    <div class="flex justify-between">
                        <dt class="text-slate-500">Tax</dt>
                        <dd>{{ number_format((float) $order->tax_amount, 2) }}</dd>
                    </div>

                    <div class="flex justify-between border-t pt-3 text-base font-bold">
                        <dt>Total</dt>
                        <dd>{{ number_format((float) $order->total_amount, 2) }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
@endsection