<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticable;

class Worker extends Authenticable
{
    use Notifiable;
    
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

    public function campaigns() {
        return $this->hasMany('App\Campaign', 'creator');
    }

    public function joined()
    {
        return $this->belongsToMany('App\Campaign', 'joined', 'worker', 'campaign');
    }
}
