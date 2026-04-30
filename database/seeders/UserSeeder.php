<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\text;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "\n CREATE FIRST ADMIN USER: Please provide below details.\n";

        if (env('ADMIN_EMAIL')) {
            $name     = env('ADMIN_NAME', 'Admin');
            $email    = env('ADMIN_EMAIL');
            $password = env('ADMIN_PASSWORD', 'password');
            echo " Using env: ADMIN_EMAIL={$email}\n";
        } else {
            $name     = text('Name:', required: true);
            $email    = text('Email:', required: true);
            $password = text('Password:', required: true);
        }

        $password = Hash::make($password);

        User::factory()->create(compact('name', 'email', 'password'));
    }
}
