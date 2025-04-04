<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->firstName;
        $lastname = fake()->lastName;
        $email = strtolower("{$name}.{$lastname}") . '@example.com';

        return [
            'name' => $name,
            'lastname' => $lastname,
            'email' => $email,
            'phone_number' => fake()->phoneNumber,
            'address' => fake()->address
        ];
    }
}
