<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('worker');
        
        Schema::create('worker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password', 255)->nullable(false);
            $table->string('name', 255)->nullable(false);
            $table->string('surname', 255)->nullable(false);
            $table->date('birthdate')->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->boolean('requester')->nullable(false)->default(false);

            $table->rememberToken();

            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('worker');
    }
}
