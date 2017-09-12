<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Country;
use App\Http\Requests\PlayerRequest;
use App\Player;
use App\Team;

use Illuminate\Support\Facades\Storage;

class PlayerController extends Controller
{
    //

    public function index()
    {
        $players = Player::orderBy('name', 'ASC')->get();
        return view('private.admin.players.index', compact('players'));
    }

    public function create()
    {
        $teams = Team::orderBy('name','ASC')->get();
        $countries = Country::orderBy('nicename','ASC')->get();
        return view('private.admin.players.create', compact('teams', 'countries'));
    }

    public function store(PlayerRequest $request)
    {

        $player = new Player;
        $player->realname = $request->navn;
        $player->name = $request->gamernavn;
        $player->slug = str_slug($request->gamernavn, '_');

        if($request->hold){
            $team = Team::findOrFail($request->hold);
            if($team) {
                $player->team_id = $team->id;
            }
        }

        if($request->land){
            $country = country::findOrFail($request->land);
            if($country) {
                $player->country_id = $country->id;
            }
        }

        if ($request->file('image')) {
            // locations for players profile image
            $filenameIMG = md5($request->gamernavn . microtime()) . ".png";
            // move file
            $file = @file_get_contents($request->image);
            Storage::disk(env('STORAGE_DISK_DRIVER'))->put('players/' . $filenameIMG, $file,
                [
                    'visibility' => 'public',
                    'CacheControl' => 'max-age=31536000'
                ]);


            $player->image = $filenameIMG;
            // if file was uploaded
        }

        if ($player->save()) {
            return redirect()->route('admin.players.index')->with('flash_message', $player->id .' er blevet oprettet!');
        }

    }

    public function edit($id)
    {
        $player = Player::findOrFail($id);
        if ($player) {
            $teams = Team::orderBy('name','ASC')->get();
            $countries = Country::orderBy('nicename','ASC')->get();
            return view('private.admin.players.edit', compact('player', 'teams', 'countries'));
        }
    }

    public function update($id, PlayerRequest $request)
    {
        $player = Player::findOrFail($id);
        if ($player) {

            $player->realname = $request->navn;
            $player->name = $request->gamernavn;
            $player->slug = str_slug($request->gamernavn, '_');


            if($request->hold){
                $team = Team::findOrFail($request->hold);
                if($team) {
                    $player->team_id = $team->id;
                }
            }

            if($request->land){
                $country = country::findOrFail($request->land);
                if($country) {
                    $player->country_id = $country->id;
                }
            }

            if ($request->file('image')) {
                // locations for players profile image
                $filenameIMG = md5($request->gamernavn . microtime()) . ".png";
                // move file
                $file = @file_get_contents($request->logo);
                $saveFile = Storage::disk(env('STORAGE_DISK_DRIVER'))->put('players/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);

                // if file was uploaded
                if($saveFile) {
                    // delete old logo if they have one
                    if($player->image) {
                        Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./players/".$player->image);
                    }
                    // add new logo to the team
                    $player->image = $filenameIMG;
                }

            }

            if ($player->save()) {
                return redirect()->route('admin.players.index')->with('flash_message', $player->id .' er blevet Opdateret!');
            }
        }
    }

    // delete one player
    public function destroy($id)
    {

        //find id by slug
        $player = Player::findOrFail($id);

        if($player->image) {
            Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./players/".$player->image);
        }

        // check to see if user is owner of the post
        if ($player->delete()) {
            return back()->with('flash_message', $player->id .' er blevet slettet!');
        }
        abort(404, 'page not found');
    }

    // delete all players
    public function destroyAll()
    {

        //find id by slug
        $players = Player::all();
        $i = 0;
        foreach($players as $player) {
            $i++;
            if($player->image) {
                Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./players/".$player->image);
            }
            $player->delete();
        }

        dump($i. ' Spillere er blevet sleettet !');
    }

}
