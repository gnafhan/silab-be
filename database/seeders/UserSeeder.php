<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed user data if environment is local
        if (app()->environment('local')) {
            DB::table('users')->insert([
                [
                    'username' => 'admin_user',
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'email' => 'admin@mail.ugm.ac.id',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'dosen_user',
                    'first_name' => 'Dosen',
                    'last_name' => 'User',
                    'email' => 'dosen@mail.ugm.ac.id',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'role' => 'dosen',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'umum_user',
                    'first_name' => 'Umum',
                    'last_name' => 'User',
                    'email' => 'umum@mail.ugm.ac.id',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                'role' => 'umum',
                'login_status' => 'off',
                'last_login' => null,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'umum_user1',
                'first_name' => 'Umum1',
                'last_name' => 'User1',
                'email' => 'umum1@mail.ugm.ac.id',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                    'role' => 'umum',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'kaleb_user',
                    'first_name' => 'Kaleb',
                    'last_name' => 'User',
                    'email' => 'kaleb@mail.ugm.ac.id',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'role' => 'kaleb',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        } else {
            // Seed user data if environment is not local
            DB::table('users')->insert([
                [
                    'username' => 'admin_user',
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'email' => 'admin@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('ADMIN_PASSWORD')),
                    'role' => 'admin',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
        
    }
}
