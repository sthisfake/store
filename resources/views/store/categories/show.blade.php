<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main>
        <section class="border-b border-slate-200 bg-slate-950 text-white">
            <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
                <nav class="flex items-center gap-2 text-sm text-white/50">
                    <a href="{{ route('home') }}" class="hover:text-white">
                        Home
                    </a>

                    <span>/</span>

                    <span>{{ $category->name }}</span>
                </nav>

                <h1 class="mt-5 text-4xl font-black tracking-tight sm:text-5xl">
                    {{ $category->name }}
                </h1>

                @if ($category->description)
                    <p class="mt-4 max-w-2xl leading-7 text-white/65">
                        {{ $category->description }}
                    </p>
                @endif
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
            <details class="mb-6 rounded-xl border border-slate-200 bg-white p-4 lg:hidden">
                <summary class="cursor-pointer font-bold">
                    Filters
                </summary>

                <div class="mt-5 border-t border-slate-200 pt-5">
                    @include('store.categories._filters')
                </div>
            </details>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_280px]">
                <div>
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-slate-500">
                            {{ $products->total() }}
                            {{ Str::plural('product', $products->total()) }}
                        </p>

                        @if ($search !== '')
                            <p class="text-sm">
                                Results for
                                <span class="font-bold">“{{ $search }}”</span>
                            </p>
                        @endif
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($products as $product)
                            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg">
                                <a
                                    href="{{ route('products.show', ['product' => $product->slug]) }}"
                                    class="block"
                                >
                                    <div class="relative aspect-square overflow-hidden bg-slate-100">
                                        @if ($product->primaryImage)
                                            <img
                                                src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            >
                                        @else
                                            <div class="grid h-full place-items-center text-sm text-slate-400">
                                                No image
                                            </div>
                                        @endif

                                        @if ($product->isOnSale())
                                            <span class="absolute left-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white">
                                                -{{ $product->discountPercentage() }}%
                                            </span>
                                        @endif
                                    </div>
                                </a>

                                <div class="p-5">
                                    <div class="flex items-center justify-between gap-3">
                                        <a
                                            href="{{ route('categories.show', ['category' => $product->category->slug]) }}"
                                            class="text-xs font-bold uppercase tracking-wider text-orange-700"
                                        >
                                            {{ $product->category->name }}
                                        </a>

                                        @if (! $product->track_inventory || $product->stock_quantity > 0)
                                            <span class="text-xs font-semibold text-emerald-700">
                                                In stock
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold text-red-600">
                                                Out of stock
                                            </span>
                                        @endif
                                    </div>

                                    <h2 class="mt-3 font-bold">
                                        <a
                                            href="{{ route('products.show', ['product' => $product->slug]) }}"
                                            class="hover:text-orange-700"
                                        >
                                            {{ $product->name }}
                                        </a>
                                    </h2>

                                    <div class="mt-4 flex items-baseline gap-3">
                                        @if ($product->isOnSale())
                                            <span class="text-lg font-black text-red-600">
                                                {{ number_format((float) $product->sale_price, 2) }}
                                            </span>

                                            <span class="text-sm text-slate-400 line-through">
                                                {{ number_format((float) $product->price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-lg font-black">
                                                {{ number_format((float) $product->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500 sm:col-span-2 xl:col-span-3">
                                No products match these filters.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                </div>

                <aside class="hidden lg:block">
                    <div class="sticky top-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="mb-5 text-lg font-black">
                            Filters
                        </h2>

                        @include('store.categories._filters')
                    </div>
                </aside>
            </div>
        </section>
    </main>

    @include('store.partials.footer')
</body>
</html>