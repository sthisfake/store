@extends('admin.layouts.app')

@section('title', 'Categories')
@section('heading', 'Categories')

@section('content')
    <div class="mb-6 flex items-center justify-end">
        <a
            href="{{ route('admin.categories.create') }}"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
        >
            Add category
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 p-4 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50 text-slate-500">
                <tr>
                    <th class="px-5 py-4">Name</th>
                    <th class="px-5 py-4">Parent</th>
                    <th class="px-5 py-4">Order</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-5 py-4">
                            <p class="font-semibold">{{ $category->name }}</p>
                            <p class="text-xs text-slate-500">{{ $category->slug }}</p>
                        </td>

                        <td class="px-5 py-4">
                            {{ $category->parentCategory?->name ?? 'None' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $category->sort_order }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('admin.categories.edit', $category) }}"
                                    class="rounded border px-3 py-1.5"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                    onsubmit="return confirm('Delete this category?')"
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
                        <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection