<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('123456789'),
        'email_verified_at'=>now(),
        'phone' => null,
        'type' => 'admin',
    ]);
    }
}
