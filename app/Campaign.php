<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
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
        'creator'
    ];

    public function joiners()
    {
        return $this->belongsToMany('App\Worker', 'joined', 'campaign', 'worker');
    }
}
