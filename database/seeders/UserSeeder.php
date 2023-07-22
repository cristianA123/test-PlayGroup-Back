<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        \App\Models\User::factory(100)->create();

        $user =  User::factory()->create([
            'name' => 'cristian',
            'lastName' => 'Chipana',
            'email' => 'cristian@gmail.com',
            'role' => 'Administrador',
            'simple_password' => '123456',
            'password' => Hash::make('123456'),
        ]);
        
    }
}
