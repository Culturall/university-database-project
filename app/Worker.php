<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $table = 'worker';
    public $timestamps = false;

    protected $fillable =   [
        'password',
        'name',
        'surname',
        'birthdate',
        'email',
        'requester'
    ];

    public function skills()
    {
        return $this->belongsToMany('App\Skill', 'has', 'worker', 'skill')->withPivot('value');
    }

    public function joined()
    {
        return $this->belongsToMany('App\Campaign', 'joined', 'worker', 'campaign');
    }
}
