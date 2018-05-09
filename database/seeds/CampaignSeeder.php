<?php

use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Campaign::class, 10)->create()->each(function ($c) {
            $workers = App\Worker::inRandomOrder()->limit(rand(0,4))->get();
            foreach ($workers as $key => $worker) {
                $c->joiners()->attach($worker->id);
            }
        });
    }
}
