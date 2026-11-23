<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('store.admin.name');
        $email = config('store.admin.email');
        $password = config('store.admin.password');

        if (! $name || ! $email || ! $password) {
            throw new RuntimeException(
                'Admin seeder environment values are missing.',
            );
        }

        User::query()->updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ],
        );
    }
}