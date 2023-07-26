<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('cluster_id');
            $table->integer('client_id');
            $table->datetime('date_received');
            $table->integer('dashboard_activity_id');
            $table->integer('client_activity_id');
            $table->longText('description');
            $table->string('status')->default('In Progress'); // In Progress, Completed
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('actual_handling_time')->nullable(); // 00:00:00:00
            $table->integer('volume')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('created_by');
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
