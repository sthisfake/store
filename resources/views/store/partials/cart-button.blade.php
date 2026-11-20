<button
    type="button"
    data-cart-open
    aria-label="Open cart"
    class="relative grid size-10 place-items-center rounded-full border border-slate-300 transition hover:border-slate-950 hover:bg-slate-100"
>
    <svg
        class="size-5"
        viewBox="0 0 24 24"
        fill="none"
        aria-hidden="true"
    >
        <path
            d="M3 4h2l2.4 10.2a2 2 0 0 0 1.95 1.55h7.9a2 2 0 0 0 1.94-1.52L21 7H6"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
        />

        <circle cx="10" cy="20" r="1" fill="currentColor"/>
        <circle cx="18" cy="20" r="1" fill="currentColor"/>
    </svg>

    @if ($headerCart['count'] > 0)
        <span class="absolute -right-2 -top-2 grid min-h-5 min-w-5 place-items-center rounded-full bg-orange-600 px-1 text-xs font-bold text-white">
            {{ $headerCart['count'] > 99 ? '99+' : $headerCart['count'] }}
        </span>
    @endif
</button>