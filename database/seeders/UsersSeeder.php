<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        [
            'name' => 'Wong',
            'email' => 'wong@gmail.com',
            'password' => Hash::make('abc123')
        ],
        [
            'name' => 'Lee',
            'email' => 'lee@gmail.com',
            'password' => Hash::make('abc123')
        ],
        [
            'name' => 'Leong',
            'email' => 'leong@gmail.com',
            'password' => Hash::make('abc123')
        ],
        [
            'name' => 'Peter',
            'email' => 'Peter@gmail.com',
            'password' => Hash::make('abc123')
        ],
        [
            'name' => 'Ali',
            'email' => 'Ali@gmail.com',
            'password' => Hash::make('abc123')
        ],
    ]);
    }
}