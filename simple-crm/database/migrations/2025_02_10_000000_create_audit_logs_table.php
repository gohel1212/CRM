<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('event'); // created, updated, deleted, login, logout, etc.
            $table->string('auditable_type')->nullable(); // Model class name
            $table->unsignedBigInteger('auditable_id')->nullable(); // Model ID
            $table->text('description')->nullable(); // Human-readable description
            $table->json('old_values')->nullable(); // Previous values (for updates)
            $table->json('new_values')->nullable(); // New values (for creates/updates)
            $table->json('properties')->nullable(); // Additional metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->timestamps();

            // Indexes for better performance
            $table->index(['user_id', 'created_at']);
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['event', 'created_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};