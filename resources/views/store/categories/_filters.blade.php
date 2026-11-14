<form
    method="GET"
    action="{{ route('categories.show', ['category' => $category->slug]) }}"
    class="space-y-6"
>
    <div>
        <label for="search" class="mb-2 block text-sm font-bold">
            Search
        </label>

        <input
            id="search"
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Product name or SKU"
            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
        >
    </div>

    @if ($filterCategories->isNotEmpty())
        <fieldset>
            <legend class="mb-3 text-sm font-bold">
                Subcategories
            </legend>

            <div class="max-h-64 space-y-2 overflow-y-auto">
                @foreach ($filterCategories as $filterCategory)
                    <label class="flex items-center gap-2 text-sm">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $filterCategory->id }}"
                            @checked(in_array($filterCategory->id, $selectedCategoryIds, true))
                            class="size-4 rounded border-slate-300"
                        >

                        {{ $filterCategory->name }}
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endif

    <fieldset>
        <legend class="mb-3 text-sm font-bold">
            Price range
        </legend>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="min_price" class="mb-1 block text-xs text-slate-500">
                    Minimum
                </label>

                <input
                    id="min_price"
                    type="number"
                    name="min_price"
                    value="{{ $minimumPrice }}"
                    min="0"
                    step="0.01"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>

            <div>
                <label for="max_price" class="mb-1 block text-xs text-slate-500">
                    Maximum
                </label>

                <input
                    id="max_price"
                    type="number"
                    name="max_price"
                    value="{{ $maximumPrice }}"
                    min="0"
                    step="0.01"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
            </div>
        </div>

        @error('max_price')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </fieldset>

    <div>
        <label for="availability" class="mb-2 block text-sm font-bold">
            Availability
        </label>

        <select
            id="availability"
            name="availability"
            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
        >
            <option value="all" @selected($availability === 'all')>
                All products
            </option>

            <option value="in_stock" @selected($availability === 'in_stock')>
                In stock
            </option>

            <option value="out_of_stock" @selected($availability === 'out_of_stock')>
                Out of stock
            </option>
        </select>
    </div>

    <label class="flex items-center gap-2 text-sm font-semibold">
        <input
            type="checkbox"
            name="sale"
            value="1"
            @checked($saleOnly)
            class="size-4 rounded border-slate-300"
        >

        Discounted products only
    </label>

    <div>
        <label for="sort" class="mb-2 block text-sm font-bold">
            Sort by
        </label>

        <select
            id="sort"
            name="sort"
            class="w-full rounded-lg border border-slate-300 px-3 py-2.5"
        >
            <option value="newest" @selected($sort === 'newest')>
                Newest
            </option>

            <option value="price_low" @selected($sort === 'price_low')>
                Price: low to high
            </option>

            <option value="price_high" @selected($sort === 'price_high')>
                Price: high to low
            </option>

            <option value="discount" @selected($sort === 'discount')>
                Biggest discount
            </option>

            <option value="name" @selected($sort === 'name')>
                Product name
            </option>
        </select>
    </div>

    <button
        type="submit"
        class="w-full rounded-lg bg-slate-950 px-4 py-3 text-sm font-bold text-white"
    >
        Apply filters
    </button>

    <a
        href="{{ route('categories.show', ['category' => $category->slug]) }}"
        class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-center text-sm font-bold"
    >
        Clear filters
    </a>
</form>