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
            $table->decimal('score', 2, 1)->nullable(false)->default(0);

            $table->rememberToken();

            $table->unique('email');
        });

        DB::statement('ALTER TABLE worker ADD CONSTRAINT chk_value_range CHECK (score >= 0 AND score <= 5);');
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
