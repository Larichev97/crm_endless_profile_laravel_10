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
        Schema::create('clients', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->integer('id_status', false, true);
            $table->integer('id_country', false, true);
            $table->integer('id_city', false, true);
            $table->string('phone_number')->unique();
            $table->string('email')->unique();
            $table->date('bdate')->nullable();
            $table->string('address')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('surname')->nullable();
            $table->string('photo_name')->nullable();
            $table->text('manager_comment')->nullable();
            $table->integer('id_user_add', false, true)->default(0);
            $table->integer('id_user_update', false, true)->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
