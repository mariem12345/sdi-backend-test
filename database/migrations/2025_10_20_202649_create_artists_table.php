<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->unique();
            $table->string('name');
            $table->json('genres')->nullable();
            $table->integer('popularity')->nullable();
            $table->integer('followers')->default(0);
            $table->string('image_url')->nullable();
            $table->string('spotify_url')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('popularity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
