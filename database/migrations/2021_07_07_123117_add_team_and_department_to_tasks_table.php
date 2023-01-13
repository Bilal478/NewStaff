<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamAndDepartmentToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
          $table->unsignedBigInteger('team_id')->nullable();
          $table->unsignedBigInteger('department_id')->nullable();

          $table->foreign('team_id')->references('id')->on('teams');
          $table->foreign('department_id')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
          $table->removeColumn('team_id');
          $table->removeColumn('department_id');
        });
    }
}
