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
        Schema::create('seo_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // e.g. 'updated_meta', 'created_schema'
            $table->string('target_model_type');
            $table->unsignedBigInteger('target_model_id');
            $table->text('details')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['target_model_type', 'target_model_id'], 'idx_target_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_audit_logs');
    }
};
