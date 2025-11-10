<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            // Generate and reuse the same hashed password for all users - creates it once using Hash::make('password') if not already set
            'password' => static::$password ??= Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'role' => RoleEnum::USER,
        ];
    }
}
