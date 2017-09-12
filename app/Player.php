<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    //
    protected $table = 'players';

    public function team()
    {
        return $this->belongsTo('App\Team');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

}
