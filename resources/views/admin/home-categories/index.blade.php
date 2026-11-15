@extends('admin.layouts.app')

@section('title', 'Home Categories')
@section('heading', 'Home Categories')

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="rounded-xl bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h2 class="text-xl font-bold">Selected categories</h2>

            <p class="mt-1 text-sm text-slate-500">
                {{ $tiles->count() }} of 10 positions used.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            @forelse ($tiles as $tile)
                <article class="overflow-hidden rounded-xl border border-slate-200">
                    <div
                        class="relative aspect-[5/2] overflow-hidden"
                        @style([
    'background-color: '.$tile->background_color,
])
                    >
                        <img
                            src="{{ asset('storage/'.$tile->image_path) }}"
                            alt="{{ $tile->category->name }}"
                            class="absolute inset-0 h-full w-full object-cover opacity-40 mix-blend-luminosity"
                        >

                        <div class="absolute inset-0 grid place-items-center p-4">
                            <p
                                class="text-center text-xl font-black"
                                style="color: {{ $tile->textColor() }}"
                            >
                                {{ $tile->category->name }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 p-4">
                        @if (! $tile->category->is_active)
                            <p class="text-sm font-semibold text-red-600">
                                This category is currently inactive.
                            </p>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('admin.home-categories.update', $tile) }}"
                            enctype="multipart/form-data"
                            class="space-y-4"
                        >
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="mb-2 block text-sm font-semibold">
                                    Replace image
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                >
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold">
                                        Color
                                    </label>

                                    <input
                                        type="color"
                                        name="background_color"
                                        value="{{ $tile->background_color }}"
                                        class="h-11 w-full rounded-lg border border-slate-300"
                                    >
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold">
                                        Order
                                    </label>

                                    <input
                                        type="number"
                                        name="sort_order"
                                        value="{{ $tile->sort_order }}"
                                        min="1"
                                        max="10"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                                    >
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-slate-900 px-4 py-2 font-semibold text-white"
                            >
                                Save changes
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('admin.home-categories.destroy', $tile) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="w-full rounded-lg border border-red-300 px-4 py-2 font-semibold text-red-600"
                            >
                                Remove
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center text-slate-500 md:col-span-2">
                    No homepage categories selected.
                </div>
            @endforelse
        </div>
    </section>

    @if ($tiles->count() < 10)
        <section class="mt-8 rounded-xl bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold">Add category</h2>

            <form
                method="POST"
                action="{{ route('admin.home-categories.store') }}"
                enctype="multipart/form-data"
                class="mt-6 grid gap-5 md:grid-cols-2"
            >
                @csrf

                <div>
                    <label for="category_id" class="mb-2 block text-sm font-semibold">
                        Category
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
                    >
                        <option value="">Select category</option>

                        @foreach ($availableCategories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id') == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold">
                        Image
                    </label>

                    <input
                        id="image"
                        type="file"
                        name="image"
                        accept="image/jpeg,image/png,image/webp"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="background_color" class="mb-2 block text-sm font-semibold">
                        Background color
                    </label>

                    <input
                        id="background_color"
                        type="color"
                        name="background_color"
                        value="{{ old('background_color', '#ea580c') }}"
                        required
                        class="h-11 w-full rounded-lg border border-slate-300"
                    >
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-semibold">
                        Display order
                    </label>

                    <input
                        id="sort_order"
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', ($tiles->count() + 1) * 10) }}"
                        min="1"
                        max="10"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-3 font-semibold text-white md:col-span-2"
                >
                    Add category
                </button>
            </form>
        </section>
    @endif
@endsection