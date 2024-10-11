<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        static $userIDBase = 240000;

        //seed 5 teachers
        DB::table('users')->insert([
            'userID' => $userIDBase++,
            'name' => 'Teacher1',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // teacher
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
        DB::table('users')->insert([
            'userID' => $userIDBase++,  // Increment the userID
            'name' => 'Teacher2',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // teacher
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
        DB::table('users')->insert([
            'userID' => $userIDBase++,  // Increment the userID
            'name' => 'Teacher3',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // teacher
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
        DB::table('users')->insert([
            'userID' => $userIDBase++,  // Increment the userID
            'name' => 'Teacher4',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // teacher
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
        DB::table('users')->insert([
            'userID' => $userIDBase++,  // Increment the userID
            'name' => 'Teacher5',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // teacher
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
        DB::table('users')->insert([
            'userID' => $userIDBase++,  // Increment the userID
            'name' => 'Teacher6',
            'password' => bcrypt('password'),  // Hash the password
            'teacher' => true,  // student
            'remember_token' => Str::random(10),  // Generate a random remember token
        ]);
    }
}
