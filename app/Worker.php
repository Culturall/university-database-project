<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Worker extends Authenticatable
{
    use Notifiable;

    protected $table = 'worker';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'birthdate', 'email', 'requester'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
