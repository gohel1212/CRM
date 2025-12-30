<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {
            if (!Schema::hasColumn('deals', 'pipeline_stage_id')) {
                $table->unsignedBigInteger('pipeline_stage_id')->nullable()->after('pipeline_id');
            }
            if (!Schema::hasColumn('deals', 'position')) {
                $table->unsignedInteger('position')->default(0)->after('pipeline_stage_id');
            }

            if (Schema::hasTable('pipeline_stages')) {
                $table->foreign('pipeline_stage_id')->references('id')->on('pipeline_stages')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('deals', function (Blueprint $table) {
            if (Schema::hasColumn('deals', 'pipeline_stage_id')) {
                $table->dropForeign(['pipeline_stage_id']);
                $table->dropColumn('pipeline_stage_id');
            }
            if (Schema::hasColumn('deals', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};


