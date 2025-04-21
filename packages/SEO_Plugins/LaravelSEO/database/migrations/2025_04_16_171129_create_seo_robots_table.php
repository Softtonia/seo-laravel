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
        Schema::create('seo_robots', function (Blueprint $table) {
            $table->id();
            $table->string('user_agent', 50)->default('*');
            $table->text('disallow')->nullable();
            $table->text('allow')->nullable();
            $table->string('sitemap_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_robots');
    }
};
