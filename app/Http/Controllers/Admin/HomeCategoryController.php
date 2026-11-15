<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\HomeCategoryTile;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;






class HomeCategoryController extends Controller
{
    public function index(): View
    {
        $tiles = HomeCategoryTile::query()->with('category:id,name,is_active')->orderBy('sort_order')->orderBy('id')->get();

        $selectedCategoryIds = $tiles->pluck('category_id');

        $availableCategories = Category::query()->where('is_active', true)->whereNotIn('id', $selectedCategoryIds)->orderBy('name')->get(['id', 'name']);

        return view('admin.home-categories.index', [
            'tiles' => $tiles,
            'availableCategories' => $availableCategories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tileCount = HomeCategoryTile::query()->count();

        if ($tileCount >= 10) {
            return back()->withErrors([
                'tile' => 'The homepage can display a maximum of 10 categories.',
            ]);
        }

        $data = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
                Rule::unique('home_category_tiles', 'category_id'),
            ],
            'image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'background_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
                Rule::unique('home_category_tiles', 'background_color'),
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
        ]);

        $maximumPosition = $tileCount + 1;

        if ($data['sort_order'] > $maximumPosition) {
            return back()->withErrors(['sort_order' => "The position must be between 1 and {$maximumPosition}."])->withInput();
        }

        $category = Category::query()->findOrFail($data['category_id']);

        if (! $category->is_active) {
            return back()->withErrors([
                'category_id' => 'Only active categories can be selected.',
            ]);
        }

        $imagePath = $request->file('image')->store('home/categories', 'public');

        DB::transaction(function () use ($data, $imagePath): void {
            
            HomeCategoryTile::query()->where('sort_order', '>=', $data['sort_order'])->increment('sort_order');

            HomeCategoryTile::query()->create([
                'category_id' => $data['category_id'],
                'image_path' => $imagePath,
                'background_color' => strtoupper(
                    $data['background_color'],
                ),
                'sort_order' => $data['sort_order'],
            ]);
        });

        return back()->with('success', 'Homepage category added.');
    }

    public function update(Request $request,HomeCategoryTile $tile): RedirectResponse 
    {
        $data = $request->validate([
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
            'background_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
                Rule::unique('home_category_tiles','background_color')->ignore($tile->id),
            ],
            'sort_order' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],
        ]);

        $tileCount = HomeCategoryTile::query()->count();
        $newPosition = (int) $data['sort_order'];

        if ($newPosition > $tileCount) {
            return back()->withErrors(['sort_order' => "The position must be between 1 and {$tileCount}."])->withInput();
        }

        $oldPosition = $tile->sort_order;
        $oldImagePath = null;
        $newImagePath = null;

        if ($request->hasFile('image')) {
            $oldImagePath = $tile->image_path;
            $newImagePath = $request->file('image')->store('home/categories', 'public');
        }

        DB::transaction(function () use ($tile,$data,$oldPosition,$newPosition,$newImagePath): void {

            if ($newPosition < $oldPosition) {
                HomeCategoryTile::query()->whereBetween('sort_order',[$newPosition, $oldPosition - 1])->increment('sort_order');
            }

            if ($newPosition > $oldPosition) {
                HomeCategoryTile::query()->whereBetween('sort_order', [$oldPosition + 1, $newPosition])->decrement('sort_order');
            }

            $changes = ['background_color' => strtoupper($data['background_color']),'sort_order' => $newPosition];

            if ($newImagePath !== null) {
                $changes['image_path'] = $newImagePath;
            }

            $tile->update($changes);
        });

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return back()->with('success', 'homepage category updated.');

    }

    public function destroy(HomeCategoryTile $tile): RedirectResponse {
        $imagePath = $tile->image_path;
        $deletedPosition = $tile->sort_order;

        DB::transaction(function () use ($tile,$deletedPosition): void {
            $tile->delete();

            HomeCategoryTile::query()->where('sort_order', '>', $deletedPosition)->decrement('sort_order');
        });

        Storage::disk('public')->delete($imagePath);

        return back()->with('success', 'homepage category removed.');
    }
}
