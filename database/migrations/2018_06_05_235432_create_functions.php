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
                	
                	FOR campaign_id IN (select J.campaign from joined as J join campaign as C on J.campaign = C.id where worker=worker_id and C.opening_date <= current_date and C.closing_date >= current_date)
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
			   		
			   		IF (best = -1.0 and selected_if_nothing IS NOT NULL) then
			   			RETURN selected_if_nothing;
			   		END IF;
			   		
			   		RETURN selected;
                	
                END
            $function$

        ');

        DB::statement('
        CREATE OR REPLACE FUNCTION public.get_campaign_leaderboard(campaign_id integer)
        RETURNS SETOF integer
        LANGUAGE plpgsql
       AS $function$
        declare
        
        BEGIN
           
           return query (select LEADERBOARD.worker from (
           select count(*) as c, S.worker 
               from campaign as C
               join task as T on C.id = T.campaign
               join task_option as T_O on T.id = T_O.task
               join selected as S on T_O.id = S.task_option
               where T.validity IS TRUE
               and S.task_option in (
                   select S.task_option
                       from selected as S
                       join task_option as T_O on S.task_option = T_O.id
                       join task as T on T_O.task = T.id
                       join campaign as C on T.campaign = C.id
                       where C.id = campaign_id
                       and T.validity IS TRUE
                       group by T.id, S.task_option
                       having count(*) >= ALL (
                           select count(*) as c
                           from selected as S
                           join task_option as T_O on S.task_option = T_O.id
                           join task as T2 on T_O.task = T2.id
                           join campaign as C on T2.campaign = C.id
                           where C.id = campaign_id
                           and T2.id = T.id
                           and T2.validity IS TRUE
                           group by S.task_option
                       )
               )
               and C.id = campaign_id
               group by S.worker
               order by c
       ) as LEADERBOARD);
                  
        END
       $function$
       
        ');

        DB::statement('
        CREATE OR REPLACE FUNCTION public.get_campaign_top_ten(campaign_id integer)
        RETURNS SETOF integer
        LANGUAGE plpgsql
       AS $function$
        declare
        
        BEGIN
           
           return query (select * from get_campaign_leaderboard(campaign_id) limit 10);
                  
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
        DB::statement("DROP FUNCTION public.get_campaign_leaderboard(campaign_id integer)");
        DB::statement("DROP FUNCTION public.get_campaign_top_ten(campaign_id integer)");
    }
}
