<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status')->default('open');
            $table->date('expected_close_date')->nullable();
            $table->unsignedBigInteger('pipeline_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Add foreign key constraints after ensuring tables exist
            if (Schema::hasTable('pipelines')) {
                $table->foreign('pipeline_id')->references('id')->on('pipelines');
            }
            if (Schema::hasTable('customers')) {
                $table->foreign('customer_id')->references('id')->on('customers');
            }
            if (Schema::hasTable('contacts')) {
                $table->foreign('contact_id')->references('id')->on('contacts');
            }
            if (Schema::hasTable('users')) {
                $table->foreign('owner_id')->references('id')->on('users');
            }
        });

        // Deal History (for tracking changes)
        Schema::create('deal_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('field_name');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deal_history');
        Schema::dropIfExists('deals');
    }
}; 