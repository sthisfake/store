<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        <div class="mb-8">
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                My account
            </p>

            <h1 class="mt-2 text-3xl font-black tracking-tight">
                Profile
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[240px_minmax(0,1fr)]">
            <aside>
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="font-bold">{{ $user->name }}</p>
                    <p class="mt-1 break-all text-sm text-slate-500">
                        {{ $user->email }}
                    </p>

                    <div class="mt-5 border-t border-slate-100 pt-5">
                        <a
                            href="{{ route('account.profile') }}"
                            class="block rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white"
                        >
                            Profile
                        </a>

                        <a
                            href="#addresses"
                            class="mt-2 block rounded-lg px-4 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100"
                        >
                            Addresses
                        </a>
                    </div>
                </div>
            </aside>

            <div class="space-y-8">
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">
                            Personal information
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Update your name, email and phone number.
                        </p>
                    </div>

                    @if ($errors->profile->any())
                        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                            {{ $errors->profile->first() }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('account.profile.update') }}"
                        class="space-y-5"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-semibold"
                            >
                                Name
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autocomplete="name"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >

                            @error('name', 'profile')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-semibold"
                            >
                                Email
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >

                            @error('email', 'profile')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-semibold"
                            >
                                Phone
                            </label>

                            <input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                maxlength="20"
                                autocomplete="tel"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >

                            @error('phone', 'profile')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600"
                        >
                            Save profile
                        </button>
                    </form>
                </section>
                <section
                    id="addresses"
                    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
                >
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">
                            Addresses
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Add and manage your delivery addresses.
                        </p>
                    </div>

                    @if ($errors->address->any())
                        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                            <ul class="space-y-1">
                                @foreach ($errors->address->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid gap-5 md:grid-cols-2">
                        @forelse ($user->addresses as $address)
                            <article class="rounded-xl border border-slate-200 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h3 class="font-bold">
                                                {{ $address->title }}
                                            </h3>

                                            @if ($address->is_default)
                                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-700">
                                                    Default
                                                </span>
                                            @endif
                                        </div>

                                        <p class="mt-3 text-sm font-semibold">
                                            {{ $address->recipient_name ?: $user->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $address->phone }}
                                        </p>

                                        <p class="mt-3 text-sm leading-6 text-slate-600">
                                            {{ $address->address }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-600">
                                            {{ $address->city }}, {{ $address->province }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-600">
                                            Postal code: {{ $address->postal_code }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                                    @if (! $address->is_default)
                                        <form
                                            method="POST"
                                            action="{{ route('account.addresses.default', $address) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="rounded-lg border border-emerald-300 px-3 py-2 text-sm font-semibold text-emerald-700"
                                            >
                                                Make default
                                            </button>
                                        </form>
                                    @endif

                                    @if ($address->orders_count === 0)
                                        <details class="w-full">
                                            <summary class="w-fit cursor-pointer rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold">
                                                Edit
                                            </summary>

                                            <form
                                                method="POST"
                                                action="{{ route('account.addresses.update', $address) }}"
                                                class="mt-4 grid gap-4 border-t border-slate-100 pt-4 sm:grid-cols-2"
                                            >
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Title
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="title"
                                                        value="{{ $address->title }}"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Recipient name
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="recipient_name"
                                                        value="{{ $address->recipient_name }}"
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Phone
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="phone"
                                                        value="{{ $address->phone }}"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Province
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="province"
                                                        value="{{ $address->province }}"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        City
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="city"
                                                        value="{{ $address->city }}"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div>
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Postal code
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="postal_code"
                                                        value="{{ $address->postal_code }}"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >
                                                </div>

                                                <div class="sm:col-span-2">
                                                    <label class="mb-1 block text-xs font-semibold">
                                                        Full address
                                                    </label>

                                                    <textarea
                                                        name="address"
                                                        rows="3"
                                                        required
                                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                                    >{{ $address->address }}</textarea>
                                                </div>

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white sm:col-span-2"
                                                >
                                                    Save address
                                                </button>
                                            </form>
                                        </details>

                                        <form
                                            method="POST"
                                            action="{{ route('account.addresses.destroy', $address) }}"
                                            onsubmit="return confirm('Delete this address?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-300 px-3 py-2 text-sm font-semibold text-red-600"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-xs text-slate-500">
                                            This address is connected to an order and cannot be edited or deleted.
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500 md:col-span-2">
                                You have not added an address yet.
                            </div>
                        @endforelse
                    </div>

                    <details class="mt-8 rounded-xl border border-slate-200 p-5">
                        <summary class="cursor-pointer font-bold">
                            Add a new address
                        </summary>

                        <form
                            method="POST"
                            action="{{ route('account.addresses.store') }}"
                            class="mt-5 grid gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2"
                        >
                            @csrf

                            <div>
                                <label for="address_title" class="mb-2 block text-sm font-semibold">
                                    Title
                                </label>

                                <input
                                    id="address_title"
                                    type="text"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="Home or Office"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div>
                                <label for="recipient_name" class="mb-2 block text-sm font-semibold">
                                    Recipient name
                                </label>

                                <input
                                    id="recipient_name"
                                    type="text"
                                    name="recipient_name"
                                    value="{{ old('recipient_name', $user->name) }}"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div>
                                <label for="address_phone" class="mb-2 block text-sm font-semibold">
                                    Phone
                                </label>

                                <input
                                    id="address_phone"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div>
                                <label for="province" class="mb-2 block text-sm font-semibold">
                                    Province
                                </label>

                                <input
                                    id="province"
                                    type="text"
                                    name="province"
                                    value="{{ old('province') }}"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div>
                                <label for="city" class="mb-2 block text-sm font-semibold">
                                    City
                                </label>

                                <input
                                    id="city"
                                    type="text"
                                    name="city"
                                    value="{{ old('city') }}"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div>
                                <label for="postal_code" class="mb-2 block text-sm font-semibold">
                                    Postal code
                                </label>

                                <input
                                    id="postal_code"
                                    type="text"
                                    name="postal_code"
                                    value="{{ old('postal_code') }}"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="full_address" class="mb-2 block text-sm font-semibold">
                                    Full address
                                </label>

                                <textarea
                                    id="full_address"
                                    name="address"
                                    rows="4"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                >{{ old('address') }}</textarea>
                            </div>

                            <label class="flex items-center gap-2 text-sm font-semibold sm:col-span-2">
                                <input
                                    type="checkbox"
                                    name="is_default"
                                    value="1"
                                    @checked(old('is_default'))
                                    class="size-4 rounded border-slate-300"
                                >

                                Use as default address
                            </label>

                            <button
                                type="submit"
                                class="rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600 sm:col-span-2"
                            >
                                Add address
                            </button>
                        </form>
                    </details>
                </section>
                <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold">
                            Change password
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Enter your current password before choosing a new one.
                        </p>
                    </div>

                    @if ($errors->password->any())
                        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                            {{ $errors->password->first() }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('account.password.update') }}"
                        class="space-y-5"
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <label
                                for="current_password"
                                class="mb-2 block text-sm font-semibold"
                            >
                                Current password
                            </label>

                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >

                            @error('current_password', 'password')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold"
                            >
                                New password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >

                            @error('password', 'password')
                                <p class="mt-2 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="password_confirmation"
                                class="mb-2 block text-sm font-semibold"
                            >
                                Confirm new password
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            >
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600"
                        >
                            Change password
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </main>

    @include('store.partials.footer')
</body>
</html>