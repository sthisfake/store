<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $categories = $this->navigationCategories();

        return view('store.account.profile', [
            'user' => $request->user(),
            'categories' => $categories,
        ]);
    }

    public function update(Request $request): RedirectResponse {

        $request->merge([
            'email' => Str::lower(
                trim((string) $request->input('email')),
            ),
        ]);

        $user = $request->user();

        $data = $request->validateWithBag('profile', [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);

        $emailChanged = $user->email !== $data['email'];

        $user->fill($data);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with(
            'success',
            'Profile updated successfully.',
        );
    }

    public function updatePassword(Request $request): RedirectResponse {

        $data = $request->validateWithBag('password', [
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ]);

        $request->user()->update([
            'password' => $data['password'],
        ]);

        $request->session()->regenerate();

        return back()->with(
            'success',
            'Password updated successfully.',
        );
    }

    private function navigationCategories()
    {
        return Category::query()->where('is_active', true)->whereNull('parent_id')->with(['childrenCategory' => function ($query): void {
                
            $query->where('is_active', true)->orderBy('sort_order')->orderBy('name')->with([

                'childrenCategory' => function ($query): void {

                    $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');

                        },
                    ]);
                },
            ])->orderBy('sort_order')->orderBy('name')->get();
    }
}