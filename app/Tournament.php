<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    //
    protected $table = 'tournaments';

    public function matches()
    {
        return $this->hasMany('App\Match', 'tournament_id');
    }

}
