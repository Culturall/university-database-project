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
                        NEW.sign_in_period_open := NEW.opening_date;
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

        // @SELECTED trigger to update task validity after inserting
        DB::statement(
            '
            CREATE OR REPLACE FUNCTION public.selected_update_task_validity()
            RETURNS trigger
            LANGUAGE plpgsql
           AS $function$
            declare
            
            required_workers int;
            threshold_percentage int;
            task_id int;
            workers real;
            workers_id int[];
            worker_id int;
            task_option_id int;
            max_answers real;
            worker_score real;
            
            BEGIN
               
               -- get task
               select task into task_id from task_option where task_option.id = NEW.task_option;
               
               -- get required workers and threshold percentage of campaign
               select campaign.required_workers, campaign.threshold_percentage into required_workers, threshold_percentage from campaign where campaign.id = 
                   (select campaign from task where task.id = task_id)
               ;
               
               -- get numbers of workers with selected option of the task
               workers := (select count(*) from selected where task_option = NEW.task_option);
               
               -- check if workers are at least the required ones
               if (required_workers > workers) then
                   return null;
               end if;
               
               -- get the number of the most answered option
               max_answers = (select max(answers.number) from (
           
           select count(*) as number from task_option join selected on task_option.id = selected.task_option where task_option.task = task_id group by selected.task_option order by number DESC
           
           ) as answers limit 1);
           
               -- check if there are more most answered option (if so return)
               if (
                   (select count(*) from (
           select max(answers.number) from (
           
           select count(*) as number from task_option join selected on task_option.id = selected.task_option where task_option.task = task_id group by selected.task_option order by number DESC
           
           ) as answers
           ) as maxes) > 1) then
               return null;
              end if;
              
              -- get task option id of most answered option
              task_option_id := (select answers.task_option from
           (
           select count(*) as number, task_option from task_option join selected on task_option.id = selected.task_option where task_option.task = task_id group by selected.task_option order by number DESC
           ) as answers limit 1);
           
               -- check if most answered option pass threshold percentage
               if (threshold_percentage > ((max_answers * 100) / workers)) then
                   return null;
               end if;
               
               -- update task validity to true
               UPDATE task SET validity = true
                 WHERE task.id = task_id;
               
               -- update score for workers which selected the most answered option
               workers_id := (select array(select worker from selected where selected.task_option = task_option_id));
               
               foreach worker_id in array workers_id
               loop
                   worker_score := (select score from worker WHERE worker.id = worker_id);
                   if (worker_score < 5) then
                       worker_score := worker_score + 0.1;
                   end if;
                   UPDATE worker SET score = worker_score
                     WHERE worker.id = worker_id;
               end loop;
               
               return null;
                      
            END
           $function$
           
            '
        );
        DB::statement('
            CREATE TRIGGER selected_update_task_validity AFTER INSERT ON selected
                FOR EACH ROW EXECUTE PROCEDURE selected_update_task_validity();
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
