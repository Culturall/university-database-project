<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Worker extends Authenticatable {
    use Notifiable;

    protected $table = 'worker';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'birthdate', 'email', 'requester', 'password', 'score',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function skills() {
        return $this->belongsToMany('App\Skill', 'has', 'worker', 'skill');
    }
    public function campaigns() {
        return $this->hasMany('App\Campaign', 'creator');
    }
    public function joined() {
        return $this->belongsToMany('App\Campaign', 'joined', 'worker', 'campaign');
    }
    public function selected() {
        return $this->belongsToMany('App\TaskOption', 'selected', 'worker', 'task_option');
    }
    public function getSelected() {
        $taskOptions = $this->selected()->using('\App\WorkerAnswer')->get();
        $result = [];
        foreach ($taskOptions as $taskOption) {
            $taskOption = \App\TaskOption::find($taskOption->pivot->task_option);
            $task = \App\Task::find($taskOption->task);
            $result[] = [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'answer' => $taskOption->name
            ];
        }

        return $result;
    }
}
