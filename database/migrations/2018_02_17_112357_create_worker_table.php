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
            $table->char('password', 32)->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('surname', 100)->nullable(false);
            $table->date('birthdate')->nullable(false);
            $table->string('email', 200)->nullable(false);
            $table->boolean('requester')->nullable(false)->default(false);

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
