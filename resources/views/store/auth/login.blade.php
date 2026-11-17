<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950 antialiased">
    <main class="flex min-h-screen items-center justify-center px-5 py-12">
        <div class="w-full max-w-md">
            <a
                href="{{ route('home') }}"
                class="mb-8 block text-center text-2xl font-black tracking-tight"
            >
                Kaka Store
            </a>

            <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm sm:p-8">
                <div class="mb-7">
                    <h1 class="text-2xl font-black">Welcome back</h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Login to continue shopping.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    class="space-y-5"
                >
                    @csrf

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
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                        >
                    </div>

                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold"
                        >
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                        >
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="size-4 rounded border-slate-300"
                        >

                        Remember me
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-slate-950 px-4 py-3 font-bold text-white transition hover:bg-orange-600"
                    >
                        Login
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    Do not have an account?

                    <a
                        href="{{ route('register') }}"
                        class="font-bold text-orange-700 hover:text-orange-600"
                    >
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </main>
</body>
</html>