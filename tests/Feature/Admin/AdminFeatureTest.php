<?php

namespace Feature\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->processPrepareAdminData();
    }

    /**
     *
     * @return void
     */
    protected function processPrepareAdminData(): void
    {
        // Текущий пользователь всегда авторизирован + Email подтвержден:
        /** @var User $user */
        $user = User::query()->create([
            'username' => 'test',
            'firstname' => 'Testfirstname',
            'lastname' => 'Testovich',
            'email' => 'test@gmail.com',
            'password' => bcrypt('test'),
            'city' => 'Днепр',
            'country' => 'Украина',
            'postal' => 49000,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->user = $user;

        $this->actingAs($user);

        Country::factory()->count(4)->create();
        City::factory()->count(6)->create();
    }
}
