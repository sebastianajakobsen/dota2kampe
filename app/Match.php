<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    //
    protected $table = 'matches';


    // Match tournament relationship. Match have one tournament
    public function tournament()
    {
        return $this->belongsTo('App\Tournament');
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

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }

//    // Match format relationship. match foramt has one format
//    public function format()
//    {
//        return $this->hasOne('App\Format', 'id', 'format_id');
//    }

    // Match winnigteam relationship. match winnigteam has one winning team
    public function winningTeam()
    {
        return $this->hasOne('App\Team', 'id', 'winning_team_id');
    }

    // Match results relationshop. Match has many results
    public function results()
    {
        return $this->hasMany('App\Result', 'match_id');
    }

//    public function comments()
//    {
//        return $this->morphMany('App\Comment', 'commentable');
//    }

    // Match streams relationshop. Match has many streams
    public function streams()
    {
        return $this->belongsToMany('App\Stream', 'matches_streams_pivot_table');
    }

}
