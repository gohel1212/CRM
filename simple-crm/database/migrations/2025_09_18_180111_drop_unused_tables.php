<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key constraints first
        Schema::table('custom_field_values', function (Blueprint $table) {
            $table->dropForeign(['custom_field_id']);
        });
        
        // Drop unused tables
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('custom_field_values');
        Schema::dropIfExists('custom_fields');
    }

    public function down()
    {
        // Recreate tables if needed (for rollback)
        // Note: This is a destructive migration, rollback not recommended
    }
};