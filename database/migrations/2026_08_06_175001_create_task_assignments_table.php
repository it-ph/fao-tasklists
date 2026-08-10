<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('cluster_id');
            $table->integer('client_id');
            $table->longText('activity_name');
            $table->date('applicable_month');
            $table->longText('client_function')->nullable();
            $table->string('eclerx_function'); // Procure to Pay (P2P), O2C, R2R, Personiv Admin, Client Admin
            $table->date('schedule')->nullable();
            $table->string('status')->default('Not Started'); // Not Started, In Progress, On-Hold, Completed
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('actual_handling_time')->nullable(); // 00:00:00:00
            $table->string('aht_in_minutes')->default(0);
            $table->string('timeliness')->nullable(); // Green / Red
            $table->string('quality')->default("Green"); // Green / Red; default Green
            $table->longText('remarks')->nullable();
            $table->integer('created_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_assignments');
    }
}
