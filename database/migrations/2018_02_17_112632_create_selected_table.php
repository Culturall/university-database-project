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
            $table->unsignedInteger('option_task');
            $table->string('option_name', 100)->nullable(false);

            $table->primary(['worker', 'option_task']);
            $table->foreign('worker')->references('id')->on('task')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['option_task', 'option_name'])->references(['task', 'name'])->on('task_option')->onUpdate('cascade')->onDelete('cascade');
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
