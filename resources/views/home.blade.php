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
                        <h4 class="mt-4 max-w-2xl text-5xl font-black tracking-tight">Products worth coming back for.</h4>
                        
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

        <section
            id="deals"
            data-product-carousel
            class="border-b border-slate-200 bg-orange-50"
        >
            <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
                <div class="mb-6">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                        Limited offers
                    </p>

                    <h2 class="mt-2 text-3xl font-black tracking-tight">
                        Biggest discounts
                    </h2>
                </div>

                @if ($discountedProducts->isNotEmpty())
                    <div class="relative">
                        @if ($discountedProducts->count() > 1)
                            <button
                                type="button"
                                data-product-previous
                                aria-label="Previous products"
                                class="absolute left-2 top-1/2 z-10 grid size-11 -translate-y-1/2 place-items-center rounded-full border border-slate-200 bg-white text-slate-900 shadow-lg transition hover:bg-slate-950 hover:text-white"
                            >
                                <svg
                                    class="size-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M15 19l-7-7 7-7"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>

                            <button
                                type="button"
                                data-product-next
                                aria-label="Next products"
                                class="absolute right-2 top-1/2 z-10 grid size-11 -translate-y-1/2 place-items-center rounded-full border border-slate-200 bg-white text-slate-900 shadow-lg transition hover:bg-slate-950 hover:text-white"
                            >
                                <svg
                                    class="size-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M9 5l7 7-7 7"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        @endif

                        <div
                            data-product-viewport
                            class="flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth px-14 pb-3 [scrollbar-width:none]"
                        >
                            @foreach ($discountedProducts as $product)
                                <article class="min-w-[250px] snap-start overflow-hidden rounded-2xl border border-orange-200 bg-white sm:min-w-[290px]">
                                    <a
                                        href="{{ route('products.show', ['product' => $product->slug]) }}"
                                        class="block"
                                    >
                                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                                            @if ($product->primaryImage)
                                                <img
                                                    src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-full w-full object-cover transition duration-300 hover:scale-105"
                                                >
                                            @else
                                                <div class="grid h-full place-items-center text-sm text-slate-400">
                                                    No image
                                                </div>
                                            @endif

                                            <span class="absolute left-3 top-3 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white">
                                                -{{ $product->discountPercentage() }}%
                                            </span>
                                        </div>
                                    </a>

                                    <div class="p-4">
                                        <p class="text-xs font-bold uppercase tracking-wider text-orange-700">
                                            {{ $product->category->name }}
                                        </p>

                                        <h3 class="mt-2 font-bold">
                                            <a
                                                href="{{ route('products.show', ['product' => $product->slug]) }}"
                                                class="hover:text-orange-700"
                                            >
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        <div class="mt-4 flex items-baseline gap-3">
                                            <span class="text-xl font-black text-red-600">
                                                {{ number_format((float) $product->sale_price, 2) }}
                                            </span>

                                            <span class="text-sm text-slate-400 line-through">
                                                {{ number_format((float) $product->price, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-orange-300 bg-white/60 p-8 text-center text-slate-500">
                        Special offers are coming soon.
                    </div>
                @endif
            </div>
        </section>

        @if ($homeCategoryTiles->isNotEmpty())
            <section id="categories" class="mx-auto max-w-7xl px-5 py-16 lg:px-8">
                <div class="mb-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                        Browse departments
                    </p>

                    <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                        What are you looking for?
                    </h2>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    @foreach ($homeCategoryTiles as $tile)
                        <a
                            href="{{ route('categories.show', ['category' => $tile->category->slug]) }}"
                            class="group relative aspect-[5/3] overflow-hidden rounded-2xl shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                            @style([
                                'background-color: '.$tile->background_color,
                            ])
                        >
                            <img
                                src="{{ asset('storage/'.$tile->image_path) }}"
                                alt="{{ $tile->category->name }}"
                                class="absolute inset-0 h-full w-full object-cover opacity-40 mix-blend-luminosity transition duration-300 group-hover:scale-105"
                            >

                            <div
                                class="absolute inset-0"
                            @style([
                                'background-image: linear-gradient(135deg, '
                                    .$tile->background_color
                                    .'E6, '
                                    .$tile->background_color
                                    .'80)',
                            ])
                            ></div>

                            <div class="absolute inset-0 grid place-items-center p-4">
                                <h3
                                    class="text-center text-base font-black sm:text-lg"
                                @style([
                                    'color: '.$tile->textColor(),
                                ])
                                >
                                    {{ $tile->category->name }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

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
