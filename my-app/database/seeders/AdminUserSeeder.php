<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'frede2000sall@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin1'),
            'is_admin' => true,
        ]);
    }
}
