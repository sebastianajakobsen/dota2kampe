<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\Tournament;

class FrontPageController extends Controller
{
    //

    public function frontpage()
    {

        $teams = Team::orderBy('name', 'DESC')->get();
        $tournaments = Tournament::orderBy('tier', 'ASC')->orderBy('start_date', 'DESC')->get();
        return view('public.frontpage', compact('teams', 'tournaments'));
    }
}
