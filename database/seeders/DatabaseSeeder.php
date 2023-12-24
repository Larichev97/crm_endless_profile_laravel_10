<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\QrProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Admin',
            'email' => 'admin@argon.com',
            'city' => 'Днепр',
            'country' => 'Украина',
            'postal' => '49018',
            'about' => 'developer',
            'password' => bcrypt('secret'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('settings')->insert([
            [
                'name' => 'QR_PROFILE_BASE_URL',
                'value' => 'http://crm-qr-laravel.loc/qrs/',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'QR_CODE_IMAGE_SIZE',
                'value' => '400',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('countries')->insert([
            [
                'name' => 'Украина',
                'iso_code' => 'ua',
                'is_active' => 1,
            ],
            [
                'name' => 'Польша',
                'iso_code' => 'pl',
                'is_active' => 1,
            ],
        ]);

        DB::table('cities')->insert([
            [
                'name' => 'Днепр',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Харьков',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Запорожье',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Киев',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Львов',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Одесса',
                'id_country' => 1,
                'is_active' => 1,
            ],
            [
                'name' => 'Варшава',
                'id_country' => 2,
                'is_active' => 1,
            ],
            [
                'name' => 'Краков',
                'id_country' => 2,
                'is_active' => 1,
            ],
            [
                'name' => 'Люблин',
                'id_country' => 2,
                'is_active' => 1,
            ],
            [
                'name' => 'Белосток',
                'id_country' => 2,
                'is_active' => 1,
            ],
            [
                'name' => 'Вроцлав',
                'id_country' => 2,
                'is_active' => 1,
            ],
        ]);


        if (in_array(env('APP_ENV'), ['loc', 'local', 'dev', 'develop'])) {
            Client::factory(20)->create();
            QrProfile::factory(35)->create();
        }
    }
}
