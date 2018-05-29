<?php

use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Worker::class, 50)->create()->each(function ($w) {
            $skills = App\Skill::inRandomOrder()->limit(rand(0,3))->get();
            foreach ($skills as $key => $skill) {
                $w->skills()->attach($skill->name);
            }
        });
    }
}
