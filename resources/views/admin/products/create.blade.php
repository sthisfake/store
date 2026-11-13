@extends('admin.layouts.app')

@section('title', 'Add Product')
@section('heading', 'Add Product')

@section('content')
    <div class="mx-auto max-w-3xl rounded-xl bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.products.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="slug" class="mb-2 block text-sm font-medium">Slug</label>
                    <input
                        id="slug"
                        type="text"
                        name="slug"
                        value="{{ old('slug') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="sku" class="mb-2 block text-sm font-medium">SKU</label>
                    <input
                        id="sku"
                        type="text"
                        name="sku"
                        value="{{ old('sku') }}"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="category_id" class="mb-2 block text-sm font-medium">
                        Category
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                        <option value="">Select category</option>

                        @foreach ($categories as $category)
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
                    <label for="price" class="mb-2 block text-sm font-medium">Price</label>
                    <input
                        id="price"
                        type="number"
                        name="price"
                        value="{{ old('price') }}"
                        min="0"
                        step="0.01"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="sale_price" class="mb-2 block text-sm font-medium">Sale price</label>
                    <input
                        id="sale_price"
                        type="number"
                        name="sale_price"
                        value="{{ old('sale_price') }}"
                        min="0"
                        step="0.01"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="stock_quantity" class="mb-2 block text-sm font-medium">
                        Stock quantity
                    </label>

                    <input
                        id="stock_quantity"
                        type="number"
                        name="stock_quantity"
                        value="{{ old('stock_quantity', 0) }}"
                        min="0"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                </div>

                <div>
                    <label for="status" class="mb-2 block text-sm font-medium">Status</label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                        @foreach ($statuses as $status)
                            <option
                                value="{{ $status->value }}"
                                @selected(old('status', 'active') === $status->value)
                            >
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="product_type" class="mb-2 block text-sm font-medium">
                        Product type
                    </label>

                    <select
                        id="product_type"
                        name="product_type"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                    >
                        @foreach ($productTypes as $productType)
                            <option
                                value="{{ $productType->value }}"
                                @selected(old('product_type', 'physical') === $productType->value)
                            >
                                {{ ucfirst($productType->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >{{ old('description') }}</textarea>
            </div>

            <div>
                <input type="hidden" name="track_inventory" value="0">

                <label class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        name="track_inventory"
                        value="1"
                        @checked((bool) old('track_inventory', true))
                    >
                    Track inventory
                </label>
            </div>

            <div
                data-image-upload
                data-max-files="8"
                data-max-size="4194304"
            >
                <label class="mb-2 block text-sm font-medium">
                    Product images
                </label>

                <label
                    data-image-dropzone
                    for="images"
                    class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center transition hover:border-slate-500 hover:bg-slate-100"
                >
                    <span class="text-sm font-semibold text-slate-700">
                        Choose images or drag them here
                    </span>

                    <span class="mt-2 text-xs text-slate-500">
                        JPG, PNG or WEBP. Maximum 8 images and 4 MB each.
                    </span>
                </label>

                <input
                    id="images"
                    type="file"
                    name="images[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    class="sr-only"
                    data-image-input
                >

                <p
                    data-image-error
                    class="mt-2 hidden text-sm text-red-600"
                ></p>

                <p
                    data-image-count
                    class="mt-3 text-sm text-slate-500"
                ></p>

                <div
                    data-image-preview
                    class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3"
                ></div>
            </div>

            <div class="flex justify-end gap-3">
                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-lg border px-4 py-2"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-white"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
@endsection
