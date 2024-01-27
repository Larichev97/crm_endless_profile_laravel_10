<?php

namespace Database\Factories;

use App\Models\QrProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QrProfile>
 */
class QrProfileFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = QrProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_client' => $this->faker->numberBetween(1, 3),
            'id_status' => $this->faker->numberBetween(1, 6),
            'id_country' => 1,
            'id_city' => $this->faker->numberBetween(1, 4),
            'birth_date' => $this->faker->dateTimeBetween('-40 years', '-20 years'),
            'death_date' => $this->faker->dateTimeBetween('-15 years', 'now'),
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->unique()->lastName,
            'surname' => $this->faker->unique()->lastName,
            'cause_death' => $this->faker->unique()->realTextBetween(10, 30),
            'profession' => $this->faker->unique()->realTextBetween(10, 30),
            'hobbies' => $this->faker->unique()->realTextBetween(10, 30),
            'biography' => $this->faker->unique()->realText,
            'last_wish' => $this->faker->unique()->realTextBetween(10, 30),
            'favourite_music_artist' => $this->faker->unique()->realTextBetween(10, 30),
            'link' => $this->faker->unique()->url,
            'geo_latitude' => $this->faker->unique()->latitude,
            'geo_longitude' => $this->faker->unique()->longitude,
            'id_user_add' => 1,
            'id_user_update' => 1,
        ];
    }
}
