@extends('admin.layouts.app')

@section('title', 'Edit Category')
@section('heading', 'Edit Category')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-6 shadow-sm">
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
            action="{{ route('admin.categories.update', $category) }}"
            class="space-y-5"
        >
            @csrf 
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $category->name) }}"
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
                    value="{{ old('slug', $category->slug) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <div>
                <label for="parent_id" class="mb-2 block text-sm font-medium">
                    Parent category
                </label>

                <select
                    id="parent_id"
                    name="parent_id"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
                    <option value="">None</option>

                    @foreach ($parentCategories as $parentCategory)
                        <option
                            value="{{ $parentCategory->id }}"
                            @selected(old('parent_id', $category->parent_id) == $parentCategory->id)
                        >
                            {{ $parentCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label for="sort_order" class="mb-2 block text-sm font-medium">
                    Sort order
                </label>

                <input
                    id="sort_order"
                    type="number"
                    name="sort_order"
                    value="{{ old('sort_order', $category->sort_order) }}"
                    min="0"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $category->is_active))
                >
                Active
            </label>

            <div class="flex justify-end gap-3">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-lg border px-4 py-2"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-white"
                >
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection