<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@moussem.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@moussem.com',
                'password' => Hash::make('Admin@1234'),
            ]
        );
    }
}
