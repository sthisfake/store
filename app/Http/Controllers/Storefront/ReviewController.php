<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request,Product $product): RedirectResponse {

        if ($product->status !== ProductStatus::ACTIVE || ! $product->category->is_active) {
            abort(404);
        }

        $data = $request->validate([
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'title' => [
                'nullable',
                'string',
                'max:150',
            ],
            'comment' => [
                'required',
                'string',
                'max:3000',
            ],
        ]);

        $review = Review::query()->where('product_id', $product->id)->where('user_id', $request->user()->id)->first();

        if ($review?->is_approved) {
            return back()->withErrors(['review' => 'An approved review cannot be replaced.']);
        }

        if ($review) {
            $review->update([
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'],
                'is_approved' => false,
                'approved_at' => null,
                'rejected_at' => null,
            ]);

            return back()->with('success','Your review was updated and submitted for approval.');
        }

        $request->user()->reviews()->create([
            'product_id' => $product->id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'],
            'is_approved' => false,
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        return back()->with('success','Your review was submitted for approval.');
    }
}