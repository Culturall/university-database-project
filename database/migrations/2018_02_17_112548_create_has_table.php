<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('has', function (Blueprint $table) {
            $table->unsignedInteger('worker')->nullable(false);
            $table->string('skill', 100)->nullable(false);
            $table->decimal('value', 2, 1)->nullable(false)->default(0);

            $table->primary(['worker', 'skill']);
            $table->foreign('worker')->references('id')->on('worker')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('skill')->references('name')->on('skill')->onUpdate('cascade')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE has ADD CONSTRAINT chk_value_range CHECK (value >= 0 AND value <= 5);');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('has');
    }
}
