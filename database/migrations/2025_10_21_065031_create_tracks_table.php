<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('spotify_id')->unique();
            $table->string('name');
            $table->integer('duration_ms');
            $table->boolean('explicit');
            $table->integer('popularity')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('external_url')->nullable();
            $table->json('artists')->nullable();
            $table->json('album')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
