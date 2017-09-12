<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Http\Requests\TournamentRequest;
use App\Tournament;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    // // TOURNAMENTS BACKEND STARTS HERE
    public function index()
    {
        $tournaments = Tournament::orderBy('name', 'ASC')->get();
        return view('private.admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {

        return view('private.admin.tournaments.create');
    }

    public function store(TournamentRequest $request)
    {

        $tournament = new Tournament;
        $tournament->name = $request->navn;
        $tournament->title = $request->title;
        $tournament->slug = str_slug($request->navn, '_');
        $tournament->tier = $request->tier;
        $tournament->wiki = $request->wiki;


        $tournament->start_date = $request->start_dato;
        $tournament->end_date = $request->slut_dato;

        if ($request->file('logo')) {
            // locations for players profile image
            $filenameIMG = md5($request->navn . microtime()) . ".png";
            // move file
            $file = @file_get_contents($request->logo);
            Storage::disk(env('STORAGE_DISK_DRIVER'))->put('players/' . $filenameIMG, $file,
                [
                    'visibility' => 'public',
                    'CacheControl' => 'max-age=31536000'
                ]);


            $tournament->logo = $filenameIMG;
            // if file was uploaded
        }

        if ($tournament->save()) {
            return redirect()->route('admin.tournaments.index')->with('flash_message', $tournament->name .' er blevet oprettet!');
        }

    }

    public function edit($id)
    {
        $tournament = Tournament::findOrFail($id);
        if ($tournament) {
            return view('private.admin.tournaments.edit', compact('tournament'));
        }
    }

    public function update($id, TournamentRequest $request)
    {
        $tournament = Tournament::findOrFail($id);
        if ($tournament) {

            $tournament->name = $request->navn;
            $tournament->title = $request->title;
            $tournament->slug = str_slug($request->navn, '_');
            $tournament->tier = $request->tier;
            $tournament->wiki = $request->wiki;


            $tournament->start_date = $request->start_dato;
            $tournament->end_date = $request->slut_dato;


            if ($request->file('logo')) {
                // locations for players profile image
                $filenameIMG = md5($request->navn . microtime()) . ".png";
                // move file
                $file = @file_get_contents($request->logo);
                $saveFile = Storage::disk(env('STORAGE_DISK_DRIVER'))->put('tournaments/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);

                // if file was uploaded
                if($saveFile) {
                    // delete old logo if they have one
                    if($tournament->logo) {
                        Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./tournaments/".$tournament->logo);
                    }
                    // add new logo to the team
                    $tournament->logo = $filenameIMG;
                }
            }

            if ($tournament->save()) {
                return redirect()->route('admin.tournaments.index')->with('flash_message', $tournament->name .' er blevet Opdateret!');
            }
        }
    }

    // delete country
    public function destroy($id)
    {

        //find id by slug
        $tournament = Tournament::findOrFail($id);

        if($tournament->logo) {
            Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./tournaments/".$tournament->logo);
        }

        // check to see if user is owner of the post
        if ($tournament->delete()) {
            return back()->with('flash_message', $tournament->name .' er blevet slettet!');
        }
        abort(404, 'page not found');
    }

    // delete all tournaments
    public function destroyAll()
    {

        //find id by slug
        $tournaments = Tournament::all();
        $i = 0;
        foreach($tournaments as $tournament) {
            $i++;
            if($tournament->logo) {
                Storage::disk(env('STORAGE_DISK_DRIVER'))->delete("./tournaments/".$tournament->logo);
            }
            $tournament->delete();
        }

        dump($i. ' Turneringer er blevet sleettet !');
    }
}
