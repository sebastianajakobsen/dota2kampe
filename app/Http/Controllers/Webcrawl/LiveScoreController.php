<?php

namespace App\Http\Controllers\Webcrawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Goutte\Client;
class LiveScoreController extends Controller
{
    //
    public function crawlLiveScore() {

        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'https://www.dotabuff.com/esports');


        $team1 = $crawler->filter('.recent-esports-matches tbody tr .series-teams .team-1 .team-text-full ')->each(function ($node) {

            return $node->text();
        });

        $team2 = $crawler->filter('.recent-esports-matches tbody tr .series-teams .team-2 .team-text-full')->each(function ($node) {
            return $node->text();

        });

        $format = $crawler->filter('.recent-esports-matches tbody tr:nth-child(2) div a')->each(function ($node) {
            return $node->text();

        });




        function liveMatchesArray($team1, $team2, $format)
        {
            foreach ($team1 as $key => $tbd) {
                $result[] = array(
                    'team1' => $team1[$key],
                    'team2' => $team2[$key],
                    'format' => $format[$key]);
            }
            return $result;
        }

        // Call the functions to make the collections
        $liveTeams = liveMatchesArray($team1, $team2, $format);

        dd($liveTeams);


    }
}
