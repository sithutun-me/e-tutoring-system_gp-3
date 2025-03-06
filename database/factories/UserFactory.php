<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = User::class;

    public function definition(): array
    {

        // Default to student role (id = 1)
        $roleId = $this->role_id ?? 1;

        // Get the role prefix based on role_id
        $prefix = $this->getRolePrefix($roleId);

        // Generate a unique user_code
        $user_code = $prefix . str_pad(
            $this->faker->unique()->numberBetween(1, 9999),
            4,
            '0',
            STR_PAD_LEFT
        );

        return [
            'user_code' => $user_code,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
        ];
    }

    private function getRolePrefix(int $roleId): string
    {
        $prefixMap = [
            1 => 'std', // Student
            2 => 'tur', // Tutor
            3 => 'stf', // Admin
        ];

        return $prefixMap[$roleId] ?? 'usr';
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
