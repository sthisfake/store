<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | Kaka Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-950 antialiased">
    @include('store.partials.header', ['categories' => $categories])

    <main>
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-5 py-5 lg:px-8">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
                @if ($product->category->parentCategory)
                    <a
                        href="{{ route('categories.show', ['category' => $product->category->parentCategory->slug]) }}"
                        class="hover:text-orange-700"
                    >
                        {{ $product->category->parentCategory->name }}
                    </a>

                    <span>/</span>
                @endif

                <a
                    href="{{ route('categories.show', ['category' => $product->category->slug]) }}"
                    class="hover:text-orange-700"
                >
                    {{ $product->category->name }}
                </a>

                <span>/</span>

                <span class="text-slate-900">
                    {{ $product->name }}
                </span>
            </nav>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl gap-10 px-5 py-10 lg:grid-cols-2 lg:px-8 lg:py-16">
            <div data-product-gallery>
                <div class="aspect-square overflow-hidden rounded-2xl border border-slate-200 bg-white">
                    @if ($primaryImage)
                        <img
                            data-gallery-main
                            src="{{ asset('storage/'.$primaryImage->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="grid h-full place-items-center bg-slate-100 text-slate-400">
                            No image available
                        </div>
                    @endif
                </div>

                @if ($product->productImages->count() > 1)
                    <div class="mt-4 grid grid-cols-4 gap-3 sm:grid-cols-5">
                        @foreach ($product->productImages as $image)
                            <button
                                type="button"
                                data-gallery-thumbnail
                                data-image-url="{{ asset('storage/'.$image->image_path) }}"
                                @class([
                                    'aspect-square overflow-hidden rounded-xl border-2 bg-white',
                                    'border-orange-500' => $primaryImage?->id === $image->id,
                                    'border-transparent' => $primaryImage?->id !== $image->id,
                                ])
                            >
                                <img
                                    src="{{ asset('storage/'.$image->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover"
                                >
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                    {{ $product->category->name }}
                </p>

                <h1 class="mt-3 text-4xl font-black leading-tight tracking-tight sm:text-5xl">
                    {{ $product->name }}
                </h1>

                <div class="mt-4 flex flex-wrap items-center gap-4">
                    @if ($reviewCount > 0)
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                            {{ number_format($averageRating, 1) }}/5
                        </span>

                        <span class="text-sm text-slate-500">
                            {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                        </span>
                    @else
                        <span class="text-sm text-slate-500">No reviews yet</span>
                    @endif
                </div>

                <div class="mt-7 flex items-baseline gap-4">
                    @if ($product->isOnSale())
                        <span class="text-4xl font-black text-red-600">
                            {{ number_format((float) $product->sale_price, 2) }}
                        </span>

                        <span class="text-xl text-slate-400 line-through">
                            {{ number_format((float) $product->price, 2) }}
                        </span>

                        <span class="rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-700">
                            Save {{ $product->discountPercentage() }}%
                        </span>
                    @else
                        <span class="text-4xl font-black">
                            {{ number_format((float) $product->price, 2) }}
                        </span>
                    @endif
                </div>

                <div class="mt-7 rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between gap-4">
                        <span class="font-semibold">Availability</span>

                        @if (! $product->track_inventory)
                            <span class="font-semibold text-emerald-700">
                                Available
                            </span>
                        @elseif ($product->stock_quantity > 0)
                            <span class="font-semibold text-emerald-700">
                                {{ $product->stock_quantity }} in stock
                            </span>
                        @else
                            <span class="font-semibold text-red-600">
                                Out of stock
                            </span>
                        @endif
                    </div>

                    @if ($product->sku)
                        <div class="mt-4 flex items-center justify-between gap-4 border-t border-slate-100 pt-4">
                            <span class="text-sm text-slate-500">SKU</span>
                            <span class="text-sm font-semibold">{{ $product->sku }}</span>
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between gap-4 border-t border-slate-100 pt-4">
                        <span class="text-sm text-slate-500">Product type</span>
                        <span class="text-sm font-semibold">
                            {{ ucfirst($product->product_type->value) }}
                        </span>
                    </div>
                </div>

                @if (! $product->track_inventory || $product->stock_quantity > 0)
                    <form
                        method="POST"
                        action="{{ route('cart.items.store', ['product' => $product->slug]) }}"
                        class="mt-6 flex gap-3"
                    >
                        @csrf

                        <div>
                            <label
                                for="quantity"
                                class="mb-1 block text-xs text-slate-500"
                            >
                                Quantity
                            </label>

                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                value="1"
                                min="1"
                                max="{{ $product->track_inventory ? $product->stock_quantity : 99 }}"
                                class="w-24 rounded-xl border border-slate-300 px-3 py-3"
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-5 flex-1 rounded-xl bg-slate-950 px-6 py-4 text-sm font-bold text-white hover:bg-orange-600"
                        >
                            Add to cart
                        </button>
                    </form>
                @else
                    <button
                        type="button"
                        disabled
                        class="mt-6 w-full cursor-not-allowed rounded-xl bg-slate-300 px-6 py-4 text-sm font-bold text-white"
                    >
                        Out of stock
                    </button>
                @endif

                <p class="mt-3 text-center text-xs text-slate-500">
                    Cart functionality will be connected in the next step.
                </p>
            </div>
        </section>

        <section class="border-y border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
                <h2 class="text-2xl font-black">Description</h2>

                <div class="mt-5 max-w-4xl whitespace-pre-line leading-8 text-slate-600">
                    {{ $product->description ?: 'No product description is available.' }}
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-700">
                        Customer feedback
                    </p>

                    <h2 class="mt-2 text-3xl font-black">
                        Reviews
                    </h2>
                </div>

                @if ($reviewCount > 0)
                    <p class="text-sm text-slate-500">
                        Average rating: {{ number_format($averageRating, 1) }}/5
                    </p>
                @endif
            </div>
                    @if (session('success'))
                        <div class="mt-8 rounded-xl bg-emerald-100 px-4 py-3 text-sm text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @error('review')
                        <div class="mt-8 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-700">
                            {{ $message }}
                        </div>
                    @enderror

                    @guest
                        <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6">
                            <p class="font-bold">Want to review this product?</p>

                            <p class="mt-2 text-sm text-slate-500">
                                Login or create an account to submit a review.
                            </p>

                            <div class="mt-4 flex gap-3">
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-bold text-white"
                                >
                                    Login
                                </a>

                                <a
                                    href="{{ route('register') }}"
                                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold"
                                >
                                    Sign up
                                </a>
                            </div>
                        </div>
                    @else
                        @if (auth()->user()->role === 'customer')
                            @if ($customerReview)
                                @if ($customerReview->is_approved)
                                    <div class="mt-8 rounded-xl border border-emerald-200 bg-emerald-50 p-5">
                                        <p class="font-bold text-emerald-800">
                                            Your review is approved.
                                        </p>

                                        <p class="mt-1 text-sm text-emerald-700">
                                            It is visible in the customer reviews below.
                                        </p>
                                    </div>
                                @elseif ($customerReview->rejected_at)
                                    <div class="mt-8 rounded-xl border border-red-200 bg-red-50 p-5">
                                        <p class="font-bold text-red-800">
                                            Your review was rejected.
                                        </p>

                                        <p class="mt-1 text-sm text-red-700">
                                            You can edit it and submit it again.
                                        </p>
                                    </div>
                                @else
                                    <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 p-5">
                                        <p class="font-bold text-amber-800">
                                            Your review is waiting for approval.
                                        </p>

                                        <p class="mt-1 text-sm text-amber-700">
                                            You can update it while it is pending.
                                        </p>
                                    </div>
                                @endif
                            @endif

                            @if ($canSubmitReview)
                                <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6">
                                    <h3 class="text-xl font-bold">
                                        {{ $customerReview ? 'Update your review' : 'Write a review' }}
                                    </h3>

                                    <form
                                        method="POST"
                                        action="{{ route('products.reviews.store', ['product' => $product->slug]) }}"
                                        class="mt-6 space-y-5"
                                    >
                                        @csrf

                                        <div>
                                            <label
                                                for="rating"
                                                class="mb-2 block text-sm font-semibold"
                                            >
                                                Rating
                                            </label>

                                            <select
                                                id="rating"
                                                name="rating"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                            >
                                                <option value="">Choose a rating</option>

                                                @for ($rating = 5; $rating >= 1; $rating--)
                                                    <option
                                                        value="{{ $rating }}"
                                                        @selected(
                                                            (int) old(
                                                                'rating',
                                                                $customerReview?->rating,
                                                            ) === $rating
                                                        )
                                                    >
                                                        {{ $rating }}/5
                                                    </option>
                                                @endfor
                                            </select>

                                            @error('rating')
                                                <p class="mt-2 text-sm text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                for="review_title"
                                                class="mb-2 block text-sm font-semibold"
                                            >
                                                Title
                                            </label>

                                            <input
                                                id="review_title"
                                                type="text"
                                                name="title"
                                                value="{{ old('title', $customerReview?->title) }}"
                                                maxlength="150"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                            >

                                            @error('title')
                                                <p class="mt-2 text-sm text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                for="review_comment"
                                                class="mb-2 block text-sm font-semibold"
                                            >
                                                Review
                                            </label>

                                            <textarea
                                                id="review_comment"
                                                name="comment"
                                                rows="5"
                                                maxlength="3000"
                                                required
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                                            >{{ old('comment', $customerReview?->comment) }}</textarea>

                                            @error('comment')
                                                <p class="mt-2 text-sm text-red-600">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-slate-950 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600"
                                        >
                                            {{ $customerReview ? 'Resubmit review' : 'Submit review' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    @endguest
            <div class="mt-8 space-y-4">
                @forelse ($reviews as $review)
                    <article class="rounded-xl border border-slate-200 bg-white p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-bold">{{ $review->user->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $review->created_at->format('M j, Y') }}
                                </p>
                            </div>

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-700">
                                {{ $review->rating }}/5
                            </span>
                        </div>

                        @if ($review->title)
                            <h3 class="mt-5 font-bold">{{ $review->title }}</h3>
                        @endif

                        <p class="mt-2 whitespace-pre-line leading-7 text-slate-600">
                            {{ $review->comment ?? 'No written comment.' }}
                        </p>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 p-8 text-slate-500">
                        This product does not have approved reviews yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $reviews->links() }}
            </div>
        </section>

        @if ($relatedProducts->isNotEmpty())
            <section class="border-t border-slate-200 bg-white">
                <div class="mx-auto max-w-7xl px-5 py-14 lg:px-8">
                    <h2 class="text-3xl font-black">Related products</h2>

                    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($relatedProducts as $relatedProduct)
                            <a
                                href="{{ route('products.show', ['product' => $relatedProduct->slug]) }}"
                                class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-1 hover:shadow-lg"
                            >
                                <div class="aspect-square overflow-hidden bg-slate-100">
                                    @if ($relatedProduct->primaryImage)
                                        <img
                                            src="{{ asset('storage/'.$relatedProduct->primaryImage->image_path) }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="h-full w-full object-cover transition group-hover:scale-105"
                                        >
                                    @else
                                        <div class="grid h-full place-items-center text-slate-400">
                                            No image
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <p class="font-bold">{{ $relatedProduct->name }}</p>

                                    <div class="mt-3 flex items-baseline gap-2">
                                        @if ($relatedProduct->isOnSale())
                                            <span class="font-black text-red-600">
                                                {{ number_format((float) $relatedProduct->sale_price, 2) }}
                                            </span>

                                            <span class="text-sm text-slate-400 line-through">
                                                {{ number_format((float) $relatedProduct->price, 2) }}
                                            </span>
                                        @else
                                            <span class="font-black">
                                                {{ number_format((float) $relatedProduct->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('store.partials.footer')
</body>
</html>