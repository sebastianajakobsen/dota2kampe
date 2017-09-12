<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Country;
use App\Http\Requests\TeamRequest;
use App\Player;
use App\Team;

use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    //

    public function index()
    {
        $teams = Team::orderBy('name', 'ASC')->get();
        $countries = Country::orderBy('nicename','ASC')->get();
        return view('private.admin.teams.index', compact('teams', 'countries'));
    }

    public function create()
    {
        $players = Player::orderBy('name', 'ASC')->get();
        $countries = Country::orderBy('nicename','ASC')->get();
        return view('private.admin.teams.create', compact('countries','players'));
    }

    public function store(TeamRequest $request)
    {

        $team = new Team;
        $team->name = $request->navn;
        $team->title = $request->title;
        $team->slug = str_slug($request->navn, '_');
        $team->wiki = $request->wiki;
        $team->tag = $request->tag;

        if($request->land){
            $country = country::findOrFail($request->land);
            if($country) {
                $team->country_id = $country->id;
            }
        }


        if ($request->file('logo')) {
            // locations for players profile image
            $filenameIMG = md5($request->navn . microtime()) . ".png";
            // move file
            $file = @file_get_contents($request->logo);
            Storage::disk(env('STORAGE_DISK_DRIVER'))->put('teams/' . $filenameIMG, $file,
                [
                    'visibility' => 'public',
                    'CacheControl' => 'max-age=31536000'
                ]);


            $team->logo = $filenameIMG;
            // if file was uploaded
        }

        if ($team->save()) {

            if($request->players) {
                Player::where('team_id', "=", $team->id)->update(['team_id' => null]);
                foreach($request->players as $name) {
                    Player::where('name', "=", $name)->update(['team_id' => $team->id]);
                }
            }

            return redirect()->route('admin.teams.index')->with('flash_message', $team->name .' er blevet oprettet!');
        }

    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        if ($team) {
            $countries = Country::orderBy('nicename','ASC')->get();
            $players = Player::orderBy('name', 'ASC')->get();
            return view('private.admin.teams.edit', compact('team', 'countries', 'players'));
        }
    }

    public function update($id, TeamRequest $request)
    {
        $team = Team::findOrFail($id);
        if ($team) {

            $team->name = $request->navn;
            $team->title = $request->title;
            $team->slug = str_slug($request->navn, '_');
            $team->wiki = $request->wiki;
            $team->tag = $request->tag;

            if($request->land){
                $country = country::findOrFail($request->land);
                if($country) {
                    $team->country_id = $country->id;
                }
            }




            if ($request->file('logo')) {
                // locations for players profile image
                $filenameIMG = md5($request->navn . microtime()) . ".png";
                // move file
                $file = @file_get_contents($request->logo);
                $saveFile = Storage::disk(env('STORAGE_DISK_DRIVER'))->put('teams/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);

                // if file was uploaded
                if($saveFile) {
                    // delete old logo if they have one
                    if($team->logo) {
                        Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./teams/".$team->logo);
                    }

                    // add new logo to the team
                    $team->logo = $filenameIMG;
                }

            }

            if ($team->save()) {

                if($request->players) {
                    Player::where('team_id', "=", $team->id)->update(['team_id' => null]);
                    foreach($request->players as $name) {
                        Player::where('name', "=", $name)->update(['team_id' => $team->id]);
                    }
                } else {
                    Player::where('team_id', "=", $team->id)->update(['team_id' => null]);
                }



                return redirect()->route('admin.teams.index')->with('flash_message', $team->name .' er blevet Opdateret!');
            }
        }
    }

    // delete country
    public function destroy($id)
    {

        //find id by slug
        $team = Team::findOrFail($id);

        if($team->logo) {
            Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./teams/".$team->logo);
        }

        // remove all players from team
        Player::where('team_id', "=", $team->id)->update(['team_id' => null]);

        // check to see if user is owner of the post
        if ($team->delete()) {
            return back()->with('flash_message', $team->name .' er blevet slettet!');
        }
        abort(404, 'page not found');
    }

    // delete all players
    public function destroyAll()
    {

        //find id by slug
        $teams = Team::all();
        $i = 0;
        foreach($teams as $team) {
            $i++;
            if($team->logo) {
                Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./teams/".$team->logo);
            }
            $team->delete();
        }

        dump($i. ' Hold er blevet sleettet !');
    }

}
