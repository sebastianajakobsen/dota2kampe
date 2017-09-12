<?php

namespace App\Http\Controllers\Webcrawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Country;
use App\Player;
use App\Team;

use Goutte\Client;

use Illuminate\Support\Facades\Storage;



class PlayerCrawlerController extends Controller
{
    //
    public function crawlPlayers()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Players_(all)');

        $playerName = $crawler->filter('table tr td:first-child + td')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $playerCountry = $crawler->filter('table tr td:first-of-type > a > img')->each(function ($node) {
            return $node->extract('alt')[0];
        });


        $playerRealname = $crawler->filter('table tr td:nth-child(3)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });



        $playerTeam = $crawler->filter('table tr td:nth-child(4)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        // loop players and remove all none players. etc values with [[ |]] it not equal != player
        // We have to check to be sure we only have players so we need to remove bad values
        foreach ($playerName as $key => $pn) {
            // check if name contains |]] if it does then it isnt a player
            if (strpos($pn, '|]]') !== false) {
                // unset the bad value from our player array
                unset($playerName[$key]);
                unset($playerRealname[$key]);
                unset($playerTeam[$key]);
            }

        }

        // reorder the array values
        $playerName = array_values($playerName);
        $playerRealname = array_values($playerRealname);
        $playerTeam = array_values($playerTeam);


        // Merge arrays functions
        function mergeArrays($playerName, $playerRealname, $playerCountry, $playerTeam)
        {
            foreach ($playerName as $key => $name) {

                $result[] = array('name' => $name, 'realname' => $playerRealname[$key], 'country' => $playerCountry[$key], 'team' => $playerTeam[$key]);
            }
            return $result;
        }


        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($playerName, $playerRealname, $playerCountry, $playerTeam);


        $i = 0;

        foreach ($mergeArray as $addPlayer) {

            $tjek = Player::Where('name', '=', $addPlayer['name'])->get();
            if (!$tjek->count()) {

                $i++;
                $player = new Player;
                $player->realname = $addPlayer['realname'];
                $player->name = $addPlayer['name'];
                $country = Country::Where('nicename', '=', $addPlayer['country'])->first();
                if ($country) {
                    $player->country_id = $country->id;
                }

                if (isset($addPlayer['team'])) {
                    $team = Team::Where('name', '=', $addPlayer['team'])->first();
                    if ($team) {
                        $player->team_id = $team->id;
                    }
                }

                $player->save();
            }
        }
        dump($i . ' Players added');

    }

    public function crawlPlayersMMR()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Leaderboards');

        $player = $crawler->filter('table tr td:first-child + td')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $mmr = $crawler->filter('table tr td:nth-child(4)')->each(function ($node) {
            $mmr = trim(preg_replace('/\s+/', ' ', $node->text()));

            return str_replace(',', '.', $mmr);
        });


        // Merge arrays functions
        function mergeArrays($player, $mmr)
        {
            foreach ($player as $key => $name) {

                $result[] = array('name' => $name, 'solo_mmr' => $mmr[$key]);
            }
            return $result;
        }

        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($player, $mmr);

        $i = 0;

        foreach ($mergeArray as $addPlayerMMR) {
            $player = Player::Where('name', '=', $addPlayerMMR['name'])->first();
            if ($player && $player->solo_mmr != $addPlayerMMR['solo_mmr']) {
                $player->solo_mmr = $addPlayerMMR['solo_mmr'];
                $i++;
                $player->save();
            }
        }
        dump($i . ' Players mmr updated added');

    }


    public function crawlPlayersImage()
    {

        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'https://www.dotabuff.com/esports/players');

        $playerName = $crawler->filter('#players-all table tr td:nth-child(2) a')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        $playerIMG = $crawler->filter('#players-all table tr td:first-child img')->each(function ($node) {
            $src = $node->extract('src');
            return $src[0];
        });


        // Merge arrays functions
        function mergeArrays($playerName, $playerIMG)
        {
            foreach ($playerName as $key => $name) {

                $result[] = array('name' => $name, 'image' => $playerIMG[$key]);
            }
            return $result;
        }

        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($playerName, $playerIMG);


        $i = 0;
        foreach ($mergeArray as $playerName) {
            $player = Player::Where('name', '=', $playerName['name'])->first();
            if ($player && $player->image == null) {


                $filenameIMG = md5($playerName['image'] . microtime()) . ".png";
                $file = @file_get_contents($playerName['image']);
                Storage::disk(env('STORAGE_DISK_DRIVER'))->put('players/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);


                $player->image = $filenameIMG;
                $i++;
                $player->save();
            }
        }
        dump($i . ' Players images has been added');


    }
}
