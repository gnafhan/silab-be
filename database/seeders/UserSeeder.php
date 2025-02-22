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
                    'password' => Hash::make('password'),
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
                    'password' => Hash::make('password'),
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
                    'password' => Hash::make('password'),
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
                    'password' => Hash::make('password'),
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
                // create 5 user role laboran with email and user lab.taj, lab.rpl, lab.ele, lab.idk, lab.ttl with email @mail.ugm.ac.id
                [
                    'username' => 'lab_taj',
                    'first_name' => 'Laboran',
                    'last_name' => 'TAJ',
                    'email' => 'lab_taj@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('LABORAN_PASSWORD')),
                    'role' => 'laboran',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'lab_rpl',
                    'first_name' => 'Laboran',
                    'last_name' => 'RPL',
                    'email' => 'lab_rpl@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('LABORAN_PASSWORD')),
                    'role' => 'laboran',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'lab_ele',
                    'first_name' => 'Laboran',
                    'last_name' => 'ELEKTRO',
                    'email' => 'lab_ele@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('LABORAN_PASSWORD')),
                    'role' => 'laboran',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'lab_idk',
                    'first_name' => 'Laboran',
                    'last_name' => 'IDK',
                    'email' => 'lab_idk@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('LABORAN_PASSWORD')),
                    'role' => 'laboran',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'username' => 'lab_ttl',
                    'first_name' => 'Laboran',
                    'last_name' => 'TTL',
                    'email' => 'lab_ttl@gmail.com',
                    'email_verified_at' => now(),
                    'password' => Hash::make(env('LABORAN_PASSWORD')),
                    'role' => 'laboran',
                    'login_status' => 'off',
                    'last_login' => null,
                    'remember_token' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
                
            ]);
        }
        
    }
}
