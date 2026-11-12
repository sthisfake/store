<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = (string) $request->query('status');

        $customers = User::query()->where('role', 'customer')->withCount('orders')->when($search !== '', function ($query) use ($search): void {

            $query->where(function ($query) use ($search): void {

                $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%");
                
                });

            })->when($status === 'active', function ($query): void {
                $query->where('is_active', true);

            })->when($status === 'inactive', function ($query): void {
                $query->where('is_active', false);

            })->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    public function show(User $customer): View
    {
        $this->ensureCustomer($customer);

        $customer->load([
            'addresses' => function ($query): void {
                $query->orderByDesc('is_default')->latest();
            },
            'orders' => function ($query): void {
                $query->latest()->limit(10);
            },
        ]);

        $customer->loadCount([
            'addresses',
            'orders',
            'reviews',
        ]);

        return view('admin.customers.show', [
            'customer' => $customer,
        ]);
    }

    public function edit(User $customer): View
    {
        $this->ensureCustomer($customer);

        return view('admin.customers.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request,User $customer): RedirectResponse {
        $this->ensureCustomer($customer);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($customer->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $customer->update($data);

        return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer updated successfully.');
    }

    public function updateStatus(Request $request,User $customer): RedirectResponse {
        $this->ensureCustomer($customer);

        $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $isActive = $request->boolean('is_active');

        $customer->update([
            'is_active' => $isActive,
        ]);

        $message = $isActive? 'Customer account activated.': 'Customer account deactivated.';

        return back()->with('success', $message);
    }

    private function ensureCustomer(User $customer): void
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }
    }
}