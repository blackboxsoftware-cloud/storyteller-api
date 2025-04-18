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
        Schema::create('service_providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('service_category_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->json('social_media')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('location')->nullable();
            $table->text('availability')->nullable();
            $table->json('preferred_genres')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};
