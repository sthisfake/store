@extends('admin.layouts.app')

@section('title', 'Reviews')
@section('heading', 'Reviews')

@section('content')
    <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4">
        <a
            href="{{ route('admin.reviews.index', ['status' => 'all']) }}"
            class="rounded-xl bg-white p-4 shadow-sm"
        >
            <p class="text-sm text-slate-500">All</p>
            <p class="mt-2 text-2xl font-bold">{{ $counts['all'] }}</p>
        </a>

        <a
            href="{{ route('admin.reviews.index', ['status' => 'pending']) }}"
            class="rounded-xl bg-white p-4 shadow-sm"
        >
            <p class="text-sm text-slate-500">Pending</p>
            <p class="mt-2 text-2xl font-bold">{{ $counts['pending'] }}</p>
        </a>

        <a
            href="{{ route('admin.reviews.index', ['status' => 'approved']) }}"
            class="rounded-xl bg-white p-4 shadow-sm"
        >
            <p class="text-sm text-slate-500">Approved</p>
            <p class="mt-2 text-2xl font-bold">{{ $counts['approved'] }}</p>
        </a>

        <a
            href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}"
            class="rounded-xl bg-white p-4 shadow-sm"
        >
            <p class="text-sm text-slate-500">Rejected</p>
            <p class="mt-2 text-2xl font-bold">{{ $counts['rejected'] }}</p>
        </a>
    </div>

    <form
        method="GET"
        action="{{ route('admin.reviews.index') }}"
        class="mb-6 grid gap-3 rounded-xl bg-white p-4 shadow-sm sm:grid-cols-[1fr_180px_auto]"
    >
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search review, product or customer"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >

        <select
            name="status"
            class="rounded-lg border border-slate-300 px-3 py-2"
        >
            <option value="all" @selected($status === 'all')>All</option>
            <option value="pending" @selected($status === 'pending')>Pending</option>
            <option value="approved" @selected($status === 'approved')>Approved</option>
            <option value="rejected" @selected($status === 'rejected')>Rejected</option>
        </select>

        <div class="flex gap-2">
            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
            >
                Filter
            </button>

            <a
                href="{{ route('admin.reviews.index') }}"
                class="rounded-lg border px-4 py-2 text-sm font-semibold"
            >
                Clear
            </a>
        </div>
    </form>

    <div class="space-y-4">
        @forelse ($reviews as $review)
            <article class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row">
                    <div>
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="font-semibold">
                                {{ $review->product?->name ?? 'Unavailable product' }}
                            </p>

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                {{ $review->rating }}/5
                            </span>

                            @if ($review->is_approved)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Approved
                                </span>
                            @elseif ($review->rejected_at)
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Rejected
                                </span>
                            @else
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    Pending
                                </span>
                            @endif
                        </div>

                        <p class="mt-2 text-sm text-slate-500">
                            By {{ $review->user->name }} · {{ $review->user->email }}
                        </p>
                    </div>

                    <p class="text-sm text-slate-500">
                        {{ $review->created_at->format('M j, Y') }}
                    </p>
                </div>

                @if ($review->title)
                    <h2 class="mt-5 font-semibold">{{ $review->title }}</h2>
                @endif

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                    {{ $review->comment ?? 'No comment provided.' }}
                </p>

                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t pt-4">
                    <div class="flex gap-3">
                        @if ($review->product)
                            <a
                                href="{{ route('admin.products.edit', $review->product) }}"
                                class="text-sm font-semibold text-blue-600"
                            >
                                View product
                            </a>
                        @endif

                        <a
                            href="{{ route('admin.customers.show', $review->user) }}"
                            class="text-sm font-semibold text-blue-600"
                        >
                            View customer
                        </a>
                    </div>

                    <div class="flex gap-2">
                        @if (! $review->is_approved)
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.approve', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white"
                                >
                                    Approve
                                </button>
                            </form>
                        @endif

                        @if ($review->is_approved || $review->rejected_at === null)
                            <form
                                method="POST"
                                action="{{ route('admin.reviews.reject', $review) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white"
                                >
                                    Reject
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-xl bg-white p-10 text-center text-slate-500 shadow-sm">
                No reviews found.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
@endsection