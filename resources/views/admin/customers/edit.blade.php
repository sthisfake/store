@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('heading', 'Edit Customer')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.customers.update', $customer) }}"
            class="space-y-5"
        >
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $customer->name) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $customer->email) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <div>
                <label for="phone" class="mb-2 block text-sm font-medium">
                    Phone
                </label>

                <input
                    id="phone"
                    type="text"
                    name="phone"
                    value="{{ old('phone', $customer->phone) }}"
                    maxlength="20"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <div class="flex justify-end gap-3">
                <a
                    href="{{ route('admin.customers.show', $customer) }}"
                    class="rounded-lg border px-4 py-2"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-white"
                >
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection