<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('permissions');
            $table->string('phone', 20)->nullable()->after('department');
            $table->string('timezone', 50)->default('UTC')->after('phone');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department', 'phone', 'timezone']);
        });
    }
};