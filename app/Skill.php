<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skill';
    protected $primaryKey = 'name';
    public $incrementing = 'false';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function workers()
    {
        return $this->belongsToMany('App\Worker', 'has', 'skill', 'worker');
    }

    public function isNeeded()
    {
        return $this->belongsToMany('App\Task', 'needs', 'skill', 'task');
    }
}
