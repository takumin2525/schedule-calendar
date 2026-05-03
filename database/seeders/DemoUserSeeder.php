<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('DemoPass2026'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Sample Member',
                'password' => Hash::make('SamplePass2026'),
            ]
        );
    }
}