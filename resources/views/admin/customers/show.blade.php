@extends('admin.layouts.app')

@section('title', 'Customer Details')
@section('heading', 'Customer Details')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a
            href="{{ route('admin.customers.index') }}"
            class="rounded-lg border bg-white px-4 py-2 text-sm font-semibold"
        >
            Back
        </a>

        <div class="flex gap-2">
            <a
                href="{{ route('admin.customers.edit', $customer) }}"
                class="rounded-lg border bg-white px-4 py-2 text-sm font-semibold"
            >
                Edit customer
            </a>

            <form
                method="POST"
                action="{{ route('admin.customers.status', $customer) }}"
            >
                @csrf
                @method('PATCH')

                <input
                    type="hidden"
                    name="is_active"
                    value="{{ $customer->is_active ? 0 : 1 }}"
                >

                <button
                    type="submit"
                    @class([
                        'rounded-lg px-4 py-2 text-sm font-semibold text-white',
                        'bg-red-600' => $customer->is_active,
                        'bg-emerald-600' => ! $customer->is_active,
                    ])
                >
                    {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-xl bg-white p-6 shadow-sm lg:col-span-2">
            <h2 class="text-lg font-semibold">{{ $customer->name }}</h2>

            <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase text-slate-500">Email</dt>
                    <dd class="mt-1">{{ $customer->email }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase text-slate-500">Phone</dt>
                    <dd class="mt-1">{{ $customer->phone ?? 'Not provided' }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase text-slate-500">Joined</dt>
                    <dd class="mt-1">{{ $customer->created_at->format('M j, Y') }}</dd>
                </div>

                <div>
                    <dt class="text-xs uppercase text-slate-500">Status</dt>
                    <dd class="mt-1">
                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section class="grid grid-cols-3 gap-3 lg:grid-cols-1">
            <div class="rounded-xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Orders</p>
                <p class="mt-2 text-2xl font-bold">{{ $customer->orders_count }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Addresses</p>
                <p class="mt-2 text-2xl font-bold">{{ $customer->addresses_count }}</p>
            </div>

            <div class="rounded-xl bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Reviews</p>
                <p class="mt-2 text-2xl font-bold">{{ $customer->reviews_count }}</p>
            </div>
        </section>
    </div>

    <section class="mt-6 rounded-xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold">Addresses</h2>

        <div class="grid gap-4 md:grid-cols-2">
            @forelse ($customer->addresses as $address)
                <article class="rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold">{{ $address->title }}</p>

                        @if ($address->is_default)
                            <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-700">
                                Default
                            </span>
                        @endif
                    </div>

                    <p class="mt-3 text-sm">{{ $address->recipient_name }}</p>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $address->province }}, {{ $address->city }}
                    </p>
                    <p class="mt-1 text-sm text-slate-600">{{ $address->address }}</p>
                    <p class="mt-1 text-sm text-slate-600">
                        Postal code: {{ $address->postal_code }}
                    </p>
                </article>
            @empty
                <p class="text-sm text-slate-500">No addresses found.</p>
            @endforelse
        </div>
    </section>

    <section class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b px-6 py-4">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Created</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($customer->orders as $order)
                        <tr>
                            <td class="px-5 py-4 font-semibold">
                                {{ $order->order_number }}
                            </td>

                            <td class="px-5 py-4">
                                {{ ucfirst($order->status->value) }}
                            </td>

                            <td class="px-5 py-4">
                                {{ number_format((float) $order->total_amount, 2) }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $order->created_at->format('M j, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-slate-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection