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
        Schema::create('provider_skills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_provider_id')->constrained()->onDelete('cascade');
            $table->string('skill_name');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_skills');
    }
};
