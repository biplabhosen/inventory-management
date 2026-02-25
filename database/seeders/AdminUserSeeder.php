<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed demo admin user.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'Demo Admin',
                'password' => 'admin12345',
            ]
        );
    }
}
