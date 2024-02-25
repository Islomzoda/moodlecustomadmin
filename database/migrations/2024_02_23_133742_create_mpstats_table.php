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
        Schema::create('mpstats', function (Blueprint $table) {
            $table->id();
            $table->integer('mpstats_id');
            $table->string('login');
            $table->string('password');
            $table->string('expire_at');
            $table->string('api_key');
            $table->string('app_link');
            $table->string('telegram_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpstats');
    }
};
