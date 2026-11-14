<header class="relative z-50 border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl items-center gap-10 px-5 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0 text-xl font-black tracking-tight">
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

                <div class="pointer-events-none invisible absolute left-0 top-full w-64 pt-2 opacity-0 transition group-hover/menu:pointer-events-auto group-hover/menu:visible group-hover/menu:opacity-100 group-focus-within/menu:pointer-events-auto group-focus-within/menu:visible group-focus-within/menu:opacity-100">
                    <div class="rounded-xl border border-slate-200 bg-white p-2 shadow-xl">
                        @forelse ($categories as $category)
                            <div class="group/category relative">
                                <a
                                    href="{{ route('categories.show', ['category' => $category->slug]) }}"
                                    class="flex items-center justify-between rounded-lg px-4 py-3 hover:bg-slate-100"
                                >
                                    <span>{{ $category->name }}</span>

                                    @if ($category->childrenCategory->isNotEmpty())
                                        <span>&gt;</span>
                                    @endif
                                </a>

                                @if ($category->childrenCategory->isNotEmpty())
                                    <div class="pointer-events-none invisible absolute left-full top-0 w-72 pl-2 opacity-0 transition group-hover/category:pointer-events-auto group-hover/category:visible group-hover/category:opacity-100">
                                        <div class="rounded-xl border border-slate-200 bg-white p-3 shadow-xl">
                                            @foreach ($category->childrenCategory as $childCategory)
                                                <div class="rounded-lg px-3 py-2 hover:bg-slate-50">
                                                    <a
                                                        href="{{ route('categories.show', ['category' => $childCategory->slug]) }}"
                                                        class="font-semibold hover:text-orange-700"
                                                    >
                                                        {{ $childCategory->name }}
                                                    </a>

                                                    @if ($childCategory->childrenCategory->isNotEmpty())
                                                        <div class="mt-2 flex flex-wrap gap-2 border-l border-slate-200 pl-3">
                                                            @foreach ($childCategory->childrenCategory as $grandchildCategory)
                                                                <a
                                                                    href="{{ route('categories.show', ['category' => $grandchildCategory->slug]) }}"
                                                                    class="text-xs font-medium text-slate-500 hover:text-orange-700"
                                                                >
                                                                    {{ $grandchildCategory->name }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
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
            </div>

            <a
                href="{{ route('home') }}#deals"
                class="hover:text-orange-700"
            >
                Deals
            </a>

            <a
                href="{{ route('home') }}#new-arrivals"
                class="hover:text-orange-700"
            >
                New arrivals
            </a>
        </nav>

        <details class="relative ml-auto md:hidden">
            <summary class="cursor-pointer list-none rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold">
                Menu
            </summary>

            <div class="absolute right-0 top-12 w-72 rounded-xl border border-slate-200 bg-white p-3 shadow-xl">
                <a
                    href="{{ route('home') }}"
                    class="block rounded-lg px-3 py-2 font-semibold hover:bg-slate-100"
                >
                    Home
                </a>

                <a
                    href="{{ route('home') }}#deals"
                    class="block rounded-lg px-3 py-2 font-semibold hover:bg-slate-100"
                >
                    Deals
                </a>

                <a
                    href="{{ route('home') }}#new-arrivals"
                    class="block rounded-lg px-3 py-2 font-semibold hover:bg-slate-100"
                >
                    New arrivals
                </a>

                <div class="mt-2 border-t border-slate-200 pt-2">
                    @foreach ($categories as $category)
                        <div class="px-3 py-2">
                            <a
                                href="{{ route('categories.show', ['category' => $category->slug]) }}"
                                class="font-semibold hover:text-orange-700"
                            >
                                {{ $category->name }}
                            </a>

                            @foreach ($category->childrenCategory as $childCategory)
                                <a
                                    href="{{ route('categories.show', ['category' => $childCategory->slug]) }}"
                                    class="mt-2 block pl-3 text-sm text-slate-500 hover:text-orange-700"
                                >
                                    {{ $childCategory->name }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </details>
    </div>
</header>