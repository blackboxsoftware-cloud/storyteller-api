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
        Schema::create('dashboard_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('theme');
            $table->boolean('email_notifications');
            $table->string('user_role');
            $table->string('default_view');
            $table->boolean('show_saved_listings');
            $table->boolean('show_recent_searches');
            $table->boolean('show_recommended_blogs');
            $table->boolean('show_review_stats');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_settings');
    }
};
