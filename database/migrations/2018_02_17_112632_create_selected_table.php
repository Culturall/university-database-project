<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected', function (Blueprint $table) {
            $table->unsignedInteger('worker');
            $table->unsignedInteger('task_option');

            $table->primary(['worker', 'task_option']);
            $table->foreign('worker')->references('id')->on('worker')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('task_option')->references('id')->on('task_option')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected');
    }
}
