<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_status' => $this->faker->numberBetween(1, 4),
            'id_country' => 1,
            'id_city' => $this->faker->numberBetween(1, 4),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'bdate' => $this->faker->date,
            'address' => $this->faker->unique()->address,
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->unique()->lastName,
            'surname' => '',
            'manager_comment' => $this->faker->unique()->realText,
            'id_user_add' => 1,
            'id_user_update' => 0,
        ];
    }
}
