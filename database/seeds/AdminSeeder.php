<?php

use App\User;
use App\AccessToken;
use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => "Admin",
            'email' => 'admin@mti.com.mm',
            'password' => Hash::make('admin'),
            'type' => 'admin',
            'photo' => '/images/user.png',
            'remember_token' => Str::random(60),
        ]);

        User::create([
            'name' => "Generator",
            'email' => 'gen@mti.com.mm',
            'password' => Hash::make('generator'),
            'type' => 'generator',
            'photo' => '/images/user.png',
            'remember_token' => Str::random(60),
        ]);

        AccessToken::create([
            'access_token' => "OtHGysY5t2CxECqpMUYstw6h1HCI0kJJq5yYbRY6nuaJ0HwfDII0uZ7nrfAm",
        ]);

        Setting::create();
    }
}
