<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        $token = Hash::make(config('app.key') . $name . $email . time());

        return [
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($this->faker->password),
            'created_at' => now(),
            'updated_at' => null
        ];
    }
}
