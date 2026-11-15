@extends('admin.layouts.app')

@section('title', 'Home Slider')
@section('heading', 'Home Slider')

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="rounded-xl bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Selected products</h2>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $sliderProducts->count() }} of 5 slider positions used.
                </p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse ($sliderProducts as $product)
                <article class="flex flex-col gap-4 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center">
                    <div class="size-24 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                        @if ($product->primaryImage)
                            <img
                                src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            <div class="grid h-full place-items-center text-xs text-slate-400">
                                No image
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate font-bold">{{ $product->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $product->category->name }}
                        </p>

                        @if ($product->status->value !== 'active')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                This product is currently inactive.
                            </p>
                        @endif
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.home-slider.order', $product) }}"
                        class="flex items-end gap-2"
                    >
                        @csrf
                        @method('PATCH')

                        <div>
                            <label
                                for="order-{{ $product->id }}"
                                class="mb-1 block text-xs text-slate-500"
                            >
                                Order
                            </label>

                            <input
                                id="order-{{ $product->id }}"
                                type="number"
                                name="home_slider_order"
                                value="{{ $product->home_slider_order }}"
                                min="0"
                                max="5"
                                class="w-24 rounded-lg border border-slate-300 px-3 py-2"
                            >
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg border border-slate-300 px-3 py-2 font-semibold"
                        >
                            Save
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('admin.home-slider.destroy', $product) }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-lg border border-red-300 px-3 py-2 font-semibold text-red-600"
                        >
                            Remove
                        </button>
                    </form>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500">
                    No products are selected for the homepage slider.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-8 rounded-xl bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-xl font-bold">Available products</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Only active products with a primary image are shown.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('admin.home-slider.index') }}"
                class="flex gap-2"
            >
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search product or SKU"
                    class="rounded-lg border border-slate-300 px-3 py-2"
                >

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white"
                >
                    Search
                </button>
            </form>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($availableProducts as $product)
                <article class="overflow-hidden rounded-xl border border-slate-200">
                    <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                        <img
                            src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    </div>

                    <div class="p-4">
                        <p class="font-bold">{{ $product->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $product->category->name }}
                        </p>

                        <form
                            method="POST"
                            action="{{ route('admin.home-slider.store', $product) }}"
                            class="mt-4"
                        >
                            @csrf

                            <button
                                type="submit"
                                @disabled($sliderProducts->count() >= 5)
                                @class([
                                    'w-full rounded-lg px-4 py-2 font-semibold text-white',
                                    'bg-slate-900 hover:bg-slate-700' => $sliderProducts->count() < 5,
                                    'cursor-not-allowed bg-slate-300' => $sliderProducts->count() >= 5,
                                ])
                            >
                                Add to slider
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-slate-500 sm:col-span-2 xl:col-span-3">
                    No available products found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $availableProducts->links() }}
        </div>
    </section>
@endsection