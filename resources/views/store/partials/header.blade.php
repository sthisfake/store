<header class="relative z-50 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="text-xl font-black tracking-tight">
            Kaka Store
        </a>

        <nav class="hidden items-center gap-7 text-sm font-semibold md:flex">
            <a href="{{ route('home') }}" class="hover:text-orange-700">
                Home
            </a>

            <div class="group/menu relative">
                <button
                    type="button"
                    class="flex items-center gap-2 py-3 hover:text-orange-700"
                >
                    Categories
                    <span class="text-xs">v</span>
                </button>

                <div class="invisible absolute left-0 top-full w-64 translate-y-2 rounded-xl border border-slate-200 bg-white p-2 opacity-0 shadow-xl transition group-hover/menu:visible group-hover/menu:translate-y-0 group-hover/menu:opacity-100 group-focus-within/menu:visible group-focus-within/menu:opacity-100">
                    @forelse ($categories as $category)
                        <div class="group/category relative">
                            <a
                                href="{{ route('home') }}#categories"
                                class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-100"
                            >
                                {{ $category->name }}

                                @if ($category->childrenCategory->isNotEmpty())
                                    <span>&gt;</span>
                                @endif
                            </a>

                            @if ($category->childrenCategory->isNotEmpty())
                                <div class="invisible absolute left-full top-0 ml-2 w-72 rounded-xl border border-slate-200 bg-white p-3 opacity-0 shadow-xl transition group-hover/category:visible group-hover/category:opacity-100">
                                    @foreach ($category->childrenCategory as $childCategory)
                                        <div class="rounded-lg px-3 py-2 hover:bg-slate-50">
                                            <p class="font-semibold">
                                                {{ $childCategory->name }}
                                            </p>

                                            @if ($childCategory->childrenCategory->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2 border-l border-slate-200 pl-3">
                                                    @foreach ($childCategory->childrenCategory as $grandchildCategory)
                                                        <span class="text-xs font-medium text-slate-500">
                                                            {{ $grandchildCategory->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="px-4 py-3 text-sm text-slate-500">
                            No categories available.
                        </p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('home') }}#deals" class="hover:text-orange-700">
                Deals
            </a>

            <a href="{{ route('home') }}#new-arrivals" class="hover:text-orange-700">
                New arrivals
            </a>
        </nav>

        <a
            href="{{ route('home') }}"
            class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold md:hidden"
        >
            Home
        </a>
    </div>
</header>