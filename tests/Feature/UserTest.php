<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{

    use RefreshDatabase;

    public function testGetUsers()
    {
        $user =  User::factory()->create([
            'name' => 'cristian',
            'lastName' => 'Chipana',
            'email' => 'chipana1@gmail.com',
            'role' => 'Administrador',
            'simple_password' => '123456',
            'password' => Hash::make('123456'),
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->get("/api/users",[
            "name"=>"agenda teste post"
        ],[
            'HTTP_Authorization'=>"Bearer ".  $token
        ]);

        $response->assertJsonStructure([
            'success',
            'users' => [
                '*' => [
                    'name',
                    'email',
                    'password',
                    'lastName',
                    'role',
                    'simple_password',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])->assertStatus(200);;

    }

    public function testAuthenticate()
    {
        $user =  User::factory()->create([
                'name' => 'cristian',
                'lastName' => 'Chipana',
                'email' => 'chipana@gmail.com',
                'role' => 'Administrador',
                'simple_password' => '123456',
                'password' => Hash::make('123456'),
            ]);

        $token = JWTAuth::fromUser($user);
        
        $response = $this->json('POST', '/api/login', [
            'email' => 'chipana@gmail.com',
            'password' => '123456',
        ]);



        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'lastName',
                'password',
                'simple_password',
                'role',
                'created_at',
                'updated_at',
            ],
        ])->assertStatus(200);
    }


     public function testCreateUser()
    {
        $user =  User::factory()->create([
            'name' => 'cristian',
            'lastName' => 'Chipana',
            'email' => 'chipana1@gmail.com',
            'role' => 'Administrador',
            'simple_password' => '123456',
            'password' => Hash::make('123456'),
        ]);

        $token = JWTAuth::fromUser($user);


        $response = $this->post("/api/user/create",[
            'name' => 'John',
            'lastName' => 'Doe',
            'role' => 'admin',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ],[
            'HTTP_Authorization'=>"Bearer ".  $token
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'success',
            'user' => [
                'id',
                'name',
                'email',
                'password',
                'lastName',
                'role',
                'simple_password',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);

        $response->assertJson([
            'success' => true,
            'user' => [
                'name' => 'John',
                'email' => 'johndoe@example.com',
                'lastName' => 'Doe',
                'role' => 'admin',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);

    }

    public function testUpdateUser()
    {

        $user =  User::factory()->create([
            'name' => 'cristian',
            'lastName' => 'Chipana',
            'email' => 'chipana1@gmail.com',
            'role' => 'Administrador',
            'simple_password' => '123456',
            'password' => Hash::make('123456'),
        ]);

        $token = JWTAuth::fromUser($user);

   
        $user2 = User::factory()->create([
            'name' => 'John',
            'lastName' => 'Doe',
            'role' => 'admin',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->put("/api/user/$user2->id",[
            'name' => 'Updated John',
            'lastName' => 'Updated Doe',
            'role' => 'updated_role',
            'email' => 'updated@example.com',
            'password' => 'updatedpassword',
        ],[
            'HTTP_Authorization'=>"Bearer ".  $token
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
        ]);
    }

    public function testDeleteUser()
{
    $user =  User::factory()->create([
        'name' => 'cristian',
        'lastName' => 'Chipana',
        'email' => 'chipana1@gmail.com',
        'role' => 'Administrador',
        'simple_password' => '123456',
        'password' => Hash::make('123456'),
    ]);

    $token = JWTAuth::fromUser($user);


    $user2 = User::factory()->create([
        'name' => 'John',
        'lastName' => 'Doe',
        'role' => 'admin',
        'email' => 'johndoe@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->delete("/api/user/$user2->id",[
    ],[
        'HTTP_Authorization'=>"Bearer ".  $token
    ]);


    $response->assertStatus(200);

    $response->assertJson([
        'success' => true,
        'message' => 'Usuario eliminado correctamente',
    ]);

}

}
