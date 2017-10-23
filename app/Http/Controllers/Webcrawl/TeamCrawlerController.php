<?php

namespace App\Http\Controllers\Webcrawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use Goutte\Client;

use Illuminate\Support\Facades\Storage;



class TeamCrawlerController extends Controller
{
    //
    public function crawlTeams()
    {

        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Portal:Teams');
        $array1 = $crawler->filter('.team-template-text')->each(function ($node) {
            return $node->text();
        });

        // Get link to wiki page for each teams in array
        $array2 = $crawler->filter('.team-template-text a')->each(function ($node) {
            $href = $node->extract('href');
            return 'http://wiki.teamliquid.net' . $href[0];
        });

        $title = $crawler->filter('.team-template-text a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });

        // Get link to image of logo of each team
        $array3 = $crawler->filter('.team-template-image img')->each(function ($node) {
            $href = $node->extract('src');
            return 'http://wiki.teamliquid.net' . $href[0];
        });


        // Merge arrays functions
        function mergeArrays($array1, $array2, $array3, $title)
        {
            foreach ($array1 as $key => $name) {
                $result[] = array('name' => $name, 'title' => $title[$key], 'wiki' => $array2[$key], 'logo' => $array3[$key]);
            }
            return $result;
        }


        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($array1, $array2, $array3, $title);

        $i = 0;
        // Get value from the merge array and store those value as a new team in DB
        foreach ($mergeArray as $teamInfo) {
            $tjek = Team::Where('name', '=', $teamInfo['name'])->get();
            if (!$tjek->count()) {
                $i++;
                $newTeam = new Team;
                $newTeam->name = $teamInfo['name'];
                $newTeam->title = $teamInfo['title'];
                $newTeam->slug = str_slug($teamInfo['name'], '_');
                $newTeam->wiki = $teamInfo['wiki'];


                $filenameIMG = md5($teamInfo['name'] . microtime()) . ".png";

                $file = @file_get_contents($teamInfo['logo']);
                Storage::disk(env('STORAGE_DISK_DRIVER'))->put('teams/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);


                $newTeam->logo = $filenameIMG;
                $newTeam->save();
            }
        }
        dump($i . ' teams added');
    }
}
