<?php

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Task::class, 120)->create()->each(function ($t) {
            $skills = App\Skill::inRandomOrder()->limit(rand(0,2))->get();
            foreach ($skills as $key => $skill) {
                $t->needs()->attach($skill->name);
            }

            $faker = Faker\Factory::create();
            for ($i=0; $i < rand(2,4); $i++) { 
                $task_option = new App\TaskOption;
                $task_option->name = $faker->text($maxNbChars = 25);
                $task_option->task = $t->id;
                $task_option->save();

                $workers = App\Worker::inRandomOrder()->limit(rand(0,5))->get();
                foreach($workers as $key => $worker) {
                    $task_option->selected()->attach($worker->id);
                }
            }
        });
    }
}
