<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition()
    {
        $roles = ['Administrador', 'Revisor'];

        return [
            'name' => fake()->name(),
            'lastName' => $this->faker->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'role' => $this->faker->randomElement($roles),
            'simple_password' => '123456',
            'password' => Hash::make('123456'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
