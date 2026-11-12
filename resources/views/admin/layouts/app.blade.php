<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
        <aside class="bg-slate-950 px-5 py-6 text-white lg:sticky lg:top-0 lg:h-screen">
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                    Store Admin
                </a>

                <span class="rounded bg-amber-400 px-2 py-1 text-xs font-bold text-slate-950">
                    ADMIN
                </span>
            </div>

            <nav class="mt-8 space-y-2">
                <a
                    href="{{ route('admin.dashboard') }}"
                    @class([
                        'block rounded-xl px-4 py-3 text-sm font-semibold transition',
                        'bg-amber-400 text-slate-950' => request()->routeIs('admin.dashboard'),
                        'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.dashboard'),
                    ])
                >
                    Dashboard
                </a>
                <a
                href="{{ route('admin.categories.index') }}"
                @class([
                    'block rounded-xl px-4 py-3 text-sm font-semibold transition',
                    'bg-amber-400 text-slate-950' => request()->routeIs('admin.categories.*'),
                    'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.categories.*'),
                ])
            >
                Categories
            </a>
            <a
                href="{{ route('admin.products.index') }}"
                @class([
                    'block rounded-xl px-4 py-3 text-sm font-semibold transition',
                    'bg-amber-400 text-slate-950' => request()->routeIs('admin.products.*'),
                    'text-slate-300 hover:bg-slate-800 hover:text-white' => ! request()->routeIs('admin.products.*'),
                ])
            >
                Products
            </a>
            </nav>
        </aside>

        <div class="min-w-0">
            <header class="flex flex-col gap-4 border-b border-slate-200 bg-white px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-600">
                        Administration
                    </p>

                    <h1 class="mt-1 text-2xl font-bold">
                        @yield('heading', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-slate-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold hover:bg-slate-100"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>