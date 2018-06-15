<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    protected $table = 'task';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'campaign',
    ];

    public function partOf() {
        return $this->belongsTo('App\Campaign', 'campaign');
    }

    public function needs() {
        return $this->belongsToMany('App\Skill', 'needs', 'task', 'skill');
    }

    public function options() {
        return $this->hasMany('App\TaskOption', 'task');
    }

    public function answer() {
        $answer = '';
        $max = 0;

        $taskOptions = $this->options;
        foreach ($taskOptions as $taskOption) {
            $actual = count($taskOption->selected);
            if ($actual > $max) {
                $max = $actual;
                $answer = $taskOption;
            }
        }

        return $answer;
    }
}

class TaskOption extends Model {
    protected $table = 'task_option';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'task',
    ];

    public function optionOf() {
        return $this->belongsTo('App\Task', 'task');
    }

    public function selected() {
        return $this->belongsToMany('App\Worker', 'selected', 'task_option', 'worker');
    }
}
