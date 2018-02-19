<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('needs', function (Blueprint $table) {
            $table->unsignedInteger('task');
            $table->string('skill', 100);

            $table->primary(['task', 'skill']);
            $table->foreign('task')->references('id')->on('task')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('skill')->references('name')->on('skill')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('needs');
    }
}
