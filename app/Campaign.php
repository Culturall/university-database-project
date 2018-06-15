<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model {
    protected $table = 'campaign';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'opening_date',
        'closing_date',
        'sign_in_period_open',
        'sign_in_period_close',
        'required_workers',
        'threshold_percentage',
        'creator',
    ];

    public function createdBy() {
        return $this->belongsTo('App\Worker', 'creator');
    }

    public function joiners() {
        return $this->belongsToMany('App\Worker', 'joined', 'campaign', 'worker');
    }

    public function tasks() {
        return $this->hasMany('App\Task', 'campaign');
    }

    public function completedTasks() {
        return $this->tasks()->where('validity', true);
    }

    public function activeTasks() {
        $tasks = $this->tasks()->where('validity', false)->get();
        $activeTasks = [];
        foreach ($tasks as $task) {
            $taskOptions = $task->options;
            foreach ($taskOptions as $taskOption) {
                if (count($taskOption->selected)) {
                    $activeTasks[] = $task;
                    break;
                }
            }
        }

        return $activeTasks;
    }
    public function inactiveTasks() {
        $tasks = $this->tasks()->where('validity', false)->get();
        $inactiveTasks = [];
        foreach ($tasks as $task) {
            $noSelected = true;
            $taskOptions = $task->options;
            foreach ($taskOptions as $taskOption) {
                if (count($taskOption->selected)) {
                    $noSelected = false;
                    break;
                }
            }
            if ($noSelected) {
                $inactiveTasks[] = $task;
            }
        }

        return $inactiveTasks;
    }
}
