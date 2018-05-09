<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // @CAMPAIGN trigger to copy one of the sign_in_period dates into the other one (only if one is null)
        DB::statement('
            CREATE OR REPLACE FUNCTION campaign_check_sign_in_period() RETURNS trigger AS $$
                BEGIN
                    -- if only one sign_in_period is set, copy that to the other one
                    IF (NEW.sign_in_period_open IS NULL AND NEW.sign_in_period_close IS NOT NULL) THEN
                        NEW.sign_in_period_open := NEW.sign_in_period_close;
                    ELSIF (NEW.sign_in_period_open IS NOT NULL AND NEW.sign_in_period_close IS NULL) THEN
                        NEW.sign_in_period_close := NEW.sign_in_period_open;
                    END IF;
                
                    RETURN NEW;
                END
            $$ LANGUAGE plpgsql;
        ');
        DB::statement('
            CREATE TRIGGER campaign_check_sign_in_period BEFORE INSERT OR UPDATE ON campaign
                FOR EACH ROW EXECUTE PROCEDURE campaign_check_sign_in_period();
        ');

        // @CAMPAIGN trigger to check if creator is a requester
        DB::statement('
            CREATE OR REPLACE FUNCTION campaign_check_creator_is_requester() RETURNS trigger AS $$
                BEGIN
                    -- ok only if creator is a requester
                    IF NOT EXISTS(SELECT * FROM worker WHERE id=NEW.creator AND requester=TRUE) THEN
                        RAISE EXCEPTION \'campaign creator must be a requester\';
                    END IF;
                    
                    RETURN NEW;
                END
            $$ LANGUAGE plpgsql;
        ');
        DB::statement('
            CREATE TRIGGER campaign_check_creator_is_requester BEFORE INSERT OR UPDATE ON campaign
                FOR EACH ROW EXECUTE PROCEDURE campaign_check_creator_is_requester();
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER campaign_check_sign_in_period ON campaign");
        DB::statement("DROP FUNCTION campaign_check_sign_in_period()");

        DB::statement("DROP TRIGGER campaign_check_creator_is_requester ON campaign");
        DB::statement("DROP FUNCTION campaign_check_creator_is_requester()");
    }
}
