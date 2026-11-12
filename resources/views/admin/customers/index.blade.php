@extends('admin.layouts.app')

@section('title', 'Customers')
@section('heading', 'Customers')

@section('content')
    <form
        method="GET"
        action="{{ route('admin.customers.index') }}"
        class="mb-6 grid gap-3 rounded-xl bg-white p-4 shadow-sm sm:grid-cols-[1fr_180px_auto]"
    >
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search name, email or phone"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >

        <select
            name="status"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >
            <option value="">All statuses</option>
            <option value="active" @selected(request('status') === 'active')>
                Active
            </option>
            <option value="inactive" @selected(request('status') === 'inactive')>
                Inactive
            </option>
        </select>

        <div class="flex gap-2">
            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.customers.index') }}"
                class="rounded-lg border px-4 py-2 text-sm font-semibold"
            >
                Clear
            </a>
        </div>
    </form>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="border-b bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-4">Customer</th>
                    <th class="px-5 py-4">Phone</th>
                    <th class="px-5 py-4">Orders</th>
                    <th class="px-5 py-4">Joined</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="px-5 py-4">
                            <p class="font-semibold">{{ $customer->name }}</p>
                            <p class="text-xs text-slate-500">{{ $customer->email }}</p>
                        </td>

                        <td class="px-5 py-4">
                            {{ $customer->phone ?? 'Not provided' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $customer->orders_count }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $customer->created_at->format('M j, Y') }}
                        </td>

                        <td class="px-5 py-4">
                            <span @class([
                                'rounded-full px-3 py-1 text-xs font-semibold',
                                'bg-emerald-100 text-emerald-700' => $customer->is_active,
                                'bg-red-100 text-red-700' => ! $customer->is_active,
                            ])>
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('admin.customers.show', $customer) }}"
                                    class="rounded border px-3 py-1.5"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route('admin.customers.edit', $customer) }}"
                                    class="rounded border px-3 py-1.5"
                                >
                                    Edit
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
                                            'rounded border px-3 py-1.5',
                                            'border-red-300 text-red-600' => $customer->is_active,
                                            'border-emerald-300 text-emerald-600' => ! $customer->is_active,
                                        ])
                                    >
                                        {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $customers->links() }}
    </div>
@endsection