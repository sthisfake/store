@extends('admin.layouts.app')

@section('title', 'Products')
@section('heading', 'Products')

@section('content')
    <div class="mb-6 flex items-center justify-end">
        <a
            href="{{ route('admin.products.create') }}"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
        >
            Add product
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="border-b bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-4">Product</th>
                    <th class="px-5 py-4">Category</th>
                    <th class="px-5 py-4">Price</th>
                    <th class="px-5 py-4">Inventory</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($products as $product)
                    <tr>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if ($product->primaryImage)
                                    <img
                                        src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="size-12 rounded-lg object-cover"
                                    >
                                @else
                                    <div class="grid size-12 place-items-center rounded-lg bg-slate-100 text-xs text-slate-400">
                                        No image
                                    </div>
                                @endif

                                <div>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $product->sku ?? $product->slug }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            {{ $product->category->name }}
                        </td>

                        <td class="px-5 py-4">
                            {{ number_format((float) $product->price, 2) }}
                        </td>

                        <td class="px-5 py-4">
                            @if ($product->track_inventory)
                                {{ $product->stock_quantity }} in stock
                            @else
                                Not tracked
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            {{ ucfirst($product->status->value) }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('admin.products.edit', $product) }}"
                                    class="rounded border px-3 py-1.5"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.products.destroy', $product) }}"
                                    onsubmit="return confirm('Delete this product?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded border border-red-300 px-3 py-1.5 text-red-600"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-500">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection