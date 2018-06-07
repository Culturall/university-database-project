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
