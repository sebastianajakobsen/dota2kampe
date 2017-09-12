<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //

    protected $table = 'countries';

    public function players()
    {
        return $this->hasMany('App\Player');
    }

    public function teams()
    {
        return $this->hasMany('App\Team');
    }
}
