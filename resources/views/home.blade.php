<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])


    <main>
        <section data-hero-carousel class="relative bg-slate-950">
            @forelse ($heroProducts as $product)
                <article
                    data-hero-slide
                    @class([
                        'relative min-h-[480px] overflow-hidden sm:min-h-[560px]',
                        'hidden' => ! $loop->first,
                    ])
                >
                    <img
                        src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                        alt="{{ $product->name }}"
                        class="absolute inset-0 h-full w-full object-cover"
                    >

                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/55 to-black/10"></div>

                    <div class="relative mx-auto flex min-h-[480px] max-w-7xl items-end px-5 py-16 text-white sm:min-h-[560px] sm:items-center lg:px-8">
                        <div class="max-w-2xl">
                            <p class="text-xs font-bold uppercase tracking-[0.3em] text-orange-400">
                                {{ $product->category->name }}
                            </p>

                            <h1 class="mt-4 text-4xl font-black leading-tight tracking-tight sm:text-6xl">
                                {{ $product->name }}
                            </h1>

                            @if ($product->description)
                                <p class="mt-5 line-clamp-2 max-w-xl text-base leading-7 text-white/75 sm:text-lg">
                                    {{ $product->description }}
                                </p>
                            @endif

                            <div class="mt-7 flex flex-wrap items-center gap-5">
                                @if ($product->isOnSale())
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-3xl font-black">{{ number_format((float) $product->sale_price, 2) }}</span>
                                        <span class="text-lg text-white/50 line-through">{{ number_format((float) $product->price, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-3xl font-black">{{ number_format((float) $product->price, 2) }}</span>
                                @endif

                                <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="rounded-full bg-orange-500 px-6 py-3 text-sm font-bold text-white transition hover:bg-orange-600">
                                    View product
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="mx-auto flex min-h-[480px] max-w-7xl items-center px-5 py-16 text-white lg:px-8">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-orange-400">Kaka Store</p>
                        <h1 class="mt-4 max-w-2xl text-5xl font-black tracking-tight">Products worth coming back for.</h1>
                        <p class="mt-5 text-white/60">Add primary images to active products to populate this slider.</p>
                    </div>
                </div>
            @endforelse

            @if ($heroProducts->count() > 1)
                <button
                    type="button"
                    data-hero-previous
                    aria-label="Previous slide"
                    class="absolute left-4 top-1/2 grid size-11 -translate-y-1/2 place-items-center rounded-full bg-black/45 text-xl text-white backdrop-blur hover:bg-black/70"
                >
                    &lt;
                </button>

                <button
                    type="button"
                    data-hero-next
                    aria-label="Next slide"
                    class="absolute right-4 top-1/2 grid size-11 -translate-y-1/2 place-items-center rounded-full bg-black/45 text-xl text-white backdrop-blur hover:bg-black/70"
                >
                    &gt;
                </button>

                <div class="absolute bottom-6 left-1/2 flex -translate-x-1/2 gap-2">
                    @foreach ($heroProducts as $product)
                        <button
                            type="button"
                            data-hero-dot="{{ $loop->index }}"
                            aria-label="Show slide {{ $loop->iteration }}"
                            @class([
                                'h-2.5 rounded-full bg-white transition-all',
                                'w-8' => $loop->first,
                                'w-2.5 opacity-50' => ! $loop->first,
                            ])
                        ></button>
                    @endforeach
                </div>
            @endif
        </section>

        <section id="deals" data-product-carousel class="border-b border-slate-200 bg-orange-50">
            <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
                <div class="mb-6 flex items-end justify-between gap-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">Limited offers</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight">Biggest discounts</h2>
                    </div>

                    @if ($discountedProducts->count() > 1)
                        <div class="flex gap-2">
                            <button type="button" data-product-previous aria-label="Previous products" class="grid size-10 place-items-center rounded-full border border-slate-300 bg-white font-bold hover:border-slate-950">&lt;</button>
                            <button type="button" data-product-next aria-label="Next products" class="grid size-10 place-items-center rounded-full border border-slate-300 bg-white font-bold hover:border-slate-950">&gt;</button>
                        </div>
                    @endif
                </div>

                @if ($discountedProducts->isNotEmpty())
                    <div data-product-viewport class="flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-3 [scrollbar-width:none]">
                        @foreach ($discountedProducts as $product)
                            <article class="min-w-[250px] snap-start overflow-hidden rounded-2xl border border-orange-200 bg-white sm:min-w-[290px]">
                                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                    @if ($product->primaryImage)
                                        <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="grid h-full place-items-center text-sm text-slate-400">No image</div>
                                    @endif

                                    <span class="absolute left-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white">
                                        -{{ $product->discountPercentage() }}%
                                    </span>
                                </div>

                                <div class="p-4">
                                    <p class="text-xs font-bold uppercase tracking-wider text-orange-700">{{ $product->category->name }}</p>
                                    <h3 class="mt-2 font-bold">
                                        <a href="{{ route('products.show', ['product' => $product->slug]) }}"class="hover:text-orange-700">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <div class="mt-4 flex items-baseline gap-3">
                                        <span class="text-xl font-black text-red-600">{{ number_format((float) $product->sale_price, 2) }}</span>
                                        <span class="text-sm text-slate-400 line-through">{{ number_format((float) $product->price, 2) }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-orange-300 bg-white/60 p-8 text-slate-500">
                        Special offers are coming soon.
                    </div>
                @endif
            </div>
        </section>

        <section id="categories" class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
            <div class="mb-8">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">Browse departments</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight">Shop by category</h2>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($categories as $category)
                    <article class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-bold">{{ $category->name }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ $category->description ?: 'Explore products in this category.' }}
                                </p>
                            </div>

                            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-orange-100 text-sm font-bold text-orange-700">
                                {{ $category->active_products_count }}
                            </span>
                        </div>

                        @if ($category->childrenCategory->isNotEmpty())
                            <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                                @foreach ($category->childrenCategory as $childCategory)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ $childCategory->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-slate-500 sm:col-span-2 lg:col-span-3">
                        No active categories are available.
                    </div>
                @endforelse
            </div>
        </section>

        <section id="new-arrivals" class="border-y border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
                <div class="mb-8">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">Latest products</p>
                    <h2 class="mt-2 text-3xl font-black tracking-tight">New arrivals</h2>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @forelse ($products as $product)
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg">
                            <div class="relative aspect-square overflow-hidden bg-slate-100">
                                @if ($product->primaryImage)
                                    <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="grid h-full place-items-center text-sm text-slate-400">No image</div>
                                @endif

                                @if ($product->isOnSale())
                                    <span class="absolute left-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white">
                                        -{{ $product->discountPercentage() }}%
                                    </span>
                                @endif
                            </div>

                            <div class="p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold uppercase tracking-wider text-orange-700">{{ $product->category->name }}</p>

                                    @if (! $product->track_inventory || $product->stock_quantity > 0)
                                        <span class="text-xs font-semibold text-emerald-700">In stock</span>
                                    @else
                                        <span class="text-xs font-semibold text-red-600">Out of stock</span>
                                    @endif
                                </div>
                                    <h3 class="mt-3 font-bold">
                                        <a href="{{ route('products.show', ['product' => $product->slug]) }}" class="hover:text-orange-700">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                <div class="mt-4 flex items-baseline gap-3">
                                    @if ($product->isOnSale())
                                        <span class="text-lg font-black text-red-600">{{ number_format((float) $product->sale_price, 2) }}</span>
                                        <span class="text-sm text-slate-400 line-through">{{ number_format((float) $product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-black">{{ number_format((float) $product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-slate-500 sm:col-span-2 lg:col-span-4">
                            No active products are available.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    @include('store.partials.footer')

</body>
</html>
