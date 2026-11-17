<footer class="bg-slate-950 text-white">
    <div class="border-b border-white/10">
        <div class="mx-auto grid max-w-7xl gap-6 px-5 py-8 sm:grid-cols-3 lg:px-8">
            <div>
                <p class="font-bold">Organized catalog</p>
                <p class="mt-1 text-sm leading-6 text-white/50">
                    Technology products arranged into clear categories.
                </p>
            </div>

            <div>
                <p class="font-bold">Stock visibility</p>
                <p class="mt-1 text-sm leading-6 text-white/50">
                    Product availability is shown before purchasing.
                </p>
            </div>

            <div>
                <p class="font-bold">Transparent discounts</p>
                <p class="mt-1 text-sm leading-6 text-white/50">
                    Regular and discounted prices are displayed together.
                </p>
            </div>
        </div>
    </div>

    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-12 sm:grid-cols-2 lg:grid-cols-5 lg:px-8">
        <div class="sm:col-span-2">
            <a
                href="{{ route('home') }}"
                class="text-2xl font-black tracking-tight"
            >
                Kaka Store
            </a>

            <p class="mt-4 max-w-md text-sm leading-7 text-white/55">
                A technology store for computers, mobile devices, gaming,
                audio, smart-home products, accessories and more.
            </p>

            <a
                class="mt-6 inline-block text-sm font-semibold text-orange-400 hover:text-orange-300"
            >
                kaka@store.com
            </a>
        </div>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-widest text-white/40">
                Shop
            </h2>

            <nav class="mt-5 space-y-3 text-sm">
                <a
                    href="{{ route('home') }}"
                    class="block text-white/70 hover:text-white"
                >
                    Home
                </a>

                <a
                    href="{{ route('home') }}#deals"
                    class="block text-white/70 hover:text-white"
                >
                    Biggest discounts
                </a>

                <a
                    href="{{ route('home') }}#categories"
                    class="block text-white/70 hover:text-white"
                >
                    Shop by category
                </a>

                <a
                    href="{{ route('home') }}#new-arrivals"
                    class="block text-white/70 hover:text-white"
                >
                    New arrivals
                </a>
            </nav>
        </div>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-widest text-white/40">
                Categories
            </h2>

            <nav class="mt-5 space-y-3 text-sm">
                @forelse ($categories->take(5) as $category)
                    <a
                        href="{{ route('categories.show', ['category' => $category->slug]) }}"
                        class="block text-white/70 hover:text-white"
                    >
                        {{ $category->name }}
                    </a>
                @empty
                    <p class="text-white/50">
                        No categories available.
                    </p>
                @endforelse
            </nav>
        </div>

        <div>
            <h2 class="text-sm font-bold uppercase tracking-widest text-white/40">
                Customer care
            </h2>

            <div class="mt-5 space-y-3 text-sm text-white/70">
                <a
                    href="mailto:{{ config('mail.from.address') }}"
                    class="block hover:text-white"
                >
                    Contact support
                </a>

                <a
                    href="{{ route('home') }}#new-arrivals"
                    class="block hover:text-white"
                >
                    Latest products
                </a>

                <a
                    href="{{ route('home') }}#categories"
                    class="block hover:text-white"
                >
                    Browse departments
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-6 text-sm text-white/40 sm:flex-row sm:items-center sm:justify-between lg:px-8">
            <p>
                &copy; {{ now()->year }} Kaka Store. All rights reserved.
            </p>

            <p>
                Prices and availability may change.
            </p>
        </div>
    </div>
</footer>