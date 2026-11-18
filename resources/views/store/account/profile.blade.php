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