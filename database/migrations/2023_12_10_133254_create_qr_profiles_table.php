<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('qr_profiles', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->foreignId('id_client')->constrained('clients');
            $table->integer('id_status', false, true);
            $table->foreignId('id_country')->constrained('countries');
            $table->foreignId('id_city')->constrained('cities');
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('surname')->nullable();
            $table->string('cause_death')->nullable();
            $table->string('profession')->nullable();
            $table->string('hobbies')->nullable();
            $table->text('biography')->nullable();
            $table->string('last_wish')->nullable();
            $table->string('favourite_music_artist')->nullable();
            $table->string('link')->nullable();
            $table->string('geo_latitude')->nullable();
            $table->string('geo_longitude')->nullable();
            $table->string('photo_file_name')->nullable();
            $table->string('voice_message_file_name')->nullable();
            $table->string('qr_code_file_name')->nullable();
            $table->foreignId('id_user_add')->constrained('users', 'id');
            $table->foreignId('id_user_update')->constrained('users', 'id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_profiles');
    }
};
