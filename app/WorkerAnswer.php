<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorkerAnswer extends Pivot {
    protected $table = 'selected';
    public $timestamps = false;

    public function worker() {
        return $this->belongsTo('Worker', 'worker');
    }
    public function taskOption() {
        return $this->belongsTo('TaskOption', 'task_option');
    }
    public function toArray()
    {
        return [
            'worker' => $this->worker,
            'taskOption' => $this->taskOption
        ];
    }
}
