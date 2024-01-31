<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFrequencyScheduleFunctionToClientActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_activities', function (Blueprint $table) {
            $table->string('function')->after('name')->nullable();
            $table->string('schedule')->after('name')->nullable();
            $table->string('frequency')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_activities', function (Blueprint $table) {
            $table->dropColumn('frequency');
            $table->dropColumn('schedule');
            $table->dropColumn('function');
        });
    }
}
