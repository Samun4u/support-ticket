<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = bcrypt(12345678);
        DB::table('users')->updateOrInsert([
            'name' => 'User One',
            'email' => 'userone@gmail.com',
            'is_admin' => 'user',
            'password' => $password,
        ]);
        DB::table('users')->updateOrInsert([
            'name' => 'Admin One',
            'email' => 'adminOne@gmail.com',
            'is_admin' => 'admin',
            'password' => $password,
        ]);
        DB::table('users')->updateOrInsert([
            'name' => 'Super Admin',
            'email' => 'superAdmin@gmail.com',
            'is_admin' => 'super_admin',
            'password' => $password,
        ]);
    }
}
