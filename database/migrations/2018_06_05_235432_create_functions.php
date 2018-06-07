<?php

use Illuminate\Database\Migrations\Migration;

class CreateFunctions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        DB::statement('
        CREATE OR REPLACE FUNCTION public.gettask(worker_id integer)
        RETURNS numeric
        LANGUAGE plpgsql
       AS $function$
       DECLARE
       i int = 1;
       skills varchar[3];
       skill varchar;
       campaign_id int;
       task_id int;
       best real = -1.0;
       last_count real;
       temp_best real;
       selected_if_nothing int;
       lowest_count real;
       selected int = null;
                       BEGIN
                           IF ((select requester from worker where id=worker_id) = TRUE) THEN
                               RETURN null;
                           END IF;

                           FOR skill IN (select has.skill from has where worker=worker_id)
                           LOOP
                               skills[i] := skill;
                               i := i + 1;
                           END LOOP;

                           FOR campaign_id IN (select campaign from joined where worker=worker_id)
                           LOOP
                               FOR task_id IN (select id from task where campaign=campaign_id AND validity=FALSE)
                               LOOP

                                   IF EXISTS(select * from selected as S join (select id from task_option where task=task_id) as P on S.task_option=P.id where s.worker=worker_id)  then
                                       CONTINUE;
                                   end if;

                                   last_count := (select count(*) from needs where task=task_id);
                                   if (last_count < lowest_count or lowest_count IS NULL) then
                                       lowest_count := last_count;
                                       selected_if_nothing := task_id;
                                   end if;

                                   IF (last_count > 0) then
                                       temp_best := (100 * (select count(*) from needs where task=task_id AND needs.skill = ANY (skills))) / last_count;
                                   else
                                       selected_if_nothing := task_id;
                                   end if;

                                   IF (temp_best > best) then
                                       best := temp_best;
                                       selected := task_id;
                                   END IF;
                               END LOOP;
                              END LOOP;

                              IF (best = 0 and selected_if_nothing IS NOT NULL) then
                                  RETURN selected_if_nothing;
                              END IF;

                              RETURN selected;

                       END
                   $function$
        ');

        DB::statement(
            '
            CREATE OR REPLACE FUNCTION public.selected_update_task_vailidity()
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement("DROP FUNCTION public.gettask(worker_id integer)");
        DB::statement("DROP FUNCTION public.selected_update_task_vailidity()");
    }
}
