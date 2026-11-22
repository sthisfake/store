<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function store(Request $request): RedirectResponse {

        $data = $request->validateWithBag(
            'address',
            $this->rules(),
        );

        $user = $request->user();

        $makeDefault = $request->boolean('is_default') || ! $user->addresses()->exists();

        DB::transaction(function () use ($user,$data,$makeDefault): void {

            if ($makeDefault) {
                $user->addresses()->update(['is_default' => false,]);
            }

            $user->addresses()->create([...$data,'is_default' => $makeDefault]);
        });

        return back()->with('success','Address added successfully.');
    }

    public function update(Request $request,Address $address): RedirectResponse {

        $address = $this->customerAddress($request,$address);

        if ($address->orders()->exists()) {
            return back()->withErrors([
                'address' => 'An address used by an order cannot be edited. Add a new address instead.',
            ], 'address');
        }

        $data = $request->validateWithBag(
            'address',
            $this->rules(),
        );

        $address->update($data);

        return back()->with('success','Address updated successfully.');
    }

    public function setDefault(Request $request,Address $address): RedirectResponse {

        $address = $this->customerAddress($request,$address);

        DB::transaction(function () use ($request,$address): void {

            $request->user()->addresses()->update([
                'is_default' => false,
            ]);

            $address->update([
                'is_default' => true,
            ]);
        });

        return back()->with('success','Default address updated.');
    }

    public function destroy(Request $request,Address $address): RedirectResponse {

        $address = $this->customerAddress($request,$address);

        if ($address->orders()->exists()) {
            return back()->withErrors([
                'address' => 'An address used by an order cannot be deleted.'
            ], 'address');
        }

        $wasDefault = $address->is_default;
        $user = $request->user();

        DB::transaction(function () use ($address,$user,$wasDefault): void {

            $address->delete();

            if ($wasDefault) {
                $nextAddress = $user->addresses()
                    ->oldest()
                    ->first();

                $nextAddress?->update([
                    'is_default' => true,
                ]);
            }
        });

        return back()->with('success','Address deleted successfully.',);
    }

    private function customerAddress(Request $request,Address $address): Address {
        return $request->user()->addresses()->whereKey($address->id)->firstOrFail();
    }

    private function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:100',
            ],
            'recipient_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
            ],
            'province' => [
                'required',
                'string',
                'max:100',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
            ],
            'postal_code' => [
                'required',
                'string',
                'max:20',
            ],
            'address' => [
                'required',
                'string',
                'max:2000',
            ],
        ];
    }
}