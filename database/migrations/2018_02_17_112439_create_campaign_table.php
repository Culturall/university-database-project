<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100)->nullable(false);
            $table->text('description', 100);
            $table->date('opening_date')->nullable(false);
            $table->date('closing_date')->nullable(false);
            $table->date('sign_in_period_open')->nullable(true);
            $table->date('sign_in_period_close')->nullable(true);
            $table->unsignedInteger('required_workers')->nullable(false)->default(0);
            $table->decimal('threshold_percentage', 3, 0)->nullable(false)->default(0);
            $table->unsignedInteger('creator')->nullable(false);

            $table->foreign('creator')->references('id')->on('worker')->onUpdate('cascade')->onDelete('set null');
        });

        DB::statement('ALTER TABLE campaign ADD CONSTRAINT chk_activity_dates CHECK (opening_date <= closing_date);');
        DB::statement('ALTER TABLE campaign ADD CONSTRAINT chk_signin_dates CHECK (sign_in_period_open <= sign_in_period_close);');
        DB::statement('ALTER TABLE campaign ADD CONSTRAINT chk_activity_signin_dates CHECK (closing_date >= sign_in_period_close);');
        DB::statement('ALTER TABLE campaign ADD CONSTRAINT chk_threshold_percentage_range CHECK (threshold_percentage >= 0 AND threshold_percentage <= 100);');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign');
    }
}
