<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = 'admin';
        $hashed_pass = Hash::make($password);

        User::create([
            'name' => 'admin',
            'email' => 'admin01@example.com',
            'password' => $hashed_pass,
            'role' => 'admin',
        ]);
    }
}
