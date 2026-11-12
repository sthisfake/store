<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;



class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = (string) $request->query('status', 'pending');

        if (! in_array($status, ['all', 'pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $query = Review::query()->with(['product:id,name,slug','user:id,name,email']);

        if ($search !== '') {

            $query->where(function ($query) use ($search): void {
                
                $query->where('title', 'like', "%{$search}%")->orWhere('comment', 'like', "%{$search}%")->orWhereHas('product', function ($query) use ($search): void {

                        $query->where('name', 'like', "%{$search}%");

                    })->orWhereHas('user', function ($query) use ($search): void {

                        $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");

                    });
            });
        }

        if ($status === 'pending') {
            $query->where('is_approved', false)->whereNull('rejected_at');
        }

        if ($status === 'approved') {
            $query->where('is_approved', true);
        }

        if ($status === 'rejected') {
            $query->where('is_approved', false)->whereNotNull('rejected_at');
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => Review::query()->count(),
            'pending' => Review::query()->where('is_approved', false)->whereNull('rejected_at')->count(),
            'approved' => Review::query()->where('is_approved', true)->count(),
            'rejected' => Review::query()->where('is_approved', false)->whereNotNull('rejected_at')->count(),
        ];

        return view('admin.reviews.index', [
            'reviews' => $reviews,
            'counts' => $counts,
            'status' => $status,
        ]);
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update([
            'is_approved' => true,
            'approved_at' => now(),
            'rejected_at' => null,
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update([
            'is_approved' => false,
            'approved_at' => null,
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Review rejected successfully.');
    }
}