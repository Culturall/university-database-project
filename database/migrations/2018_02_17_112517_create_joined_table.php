<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoinedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joined', function (Blueprint $table) {
            $table->unsignedInteger('worker')->nullable(false);
            $table->unsignedInteger('campaign')->nullable(false);

            $table->primary(['worker', 'campaign']);
            $table->foreign('worker')->references('id')->on('worker')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('campaign')->references('id')->on('campaign')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('joined');
    }
}
