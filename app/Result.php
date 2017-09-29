<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //

    public function match()
    {
        return $this->belongsTo('App\Match');
    }

    // Match team1 relationship. match team1 has one team
    public function team1()
    {
        return $this->hasOne('App\Team', 'id', 'team1_id');
    }

    // Match team2 relationship. match team2 has one team
    public function team2()
    {
        return $this->hasOne('App\Team', 'id', 'team2_id');
    }


    // Match winnigteam relationship. match winnigteam has one winning team
    public function winningTeam()
    {
        return $this->hasOne('App\Team', 'id', 'winning_team_id');
    }
}
