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

        DB::table('users')->insert([
            'username' => 'Twinti',
            'firstname' => 'Денис',
            'lastname' => 'Ларичев',
            'email' => 'denis.larichev97@gmail.com',
            'address' => 'г. Днепр, ул. Заливная',
            'city' => 'Днепр',
            'country' => 'Украина',
            'postal' => '49018',
            'about' => 'developer',
            'password' => bcrypt('qweqwe123123'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Client::factory(50)->create();
        QrProfile::factory(100)->create();
    }
}
