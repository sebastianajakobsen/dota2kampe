<?php

namespace App\Http\Controllers\Webcrawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Match;

use App\Team;
use App\Tournament;
use Goutte\Client;
use DateTime;
use DateTimeZone;


// we need to fix this shit
class MatchCrawlerController extends Controller
{
    //
    // This is working
    public function crawlTournamentsMatches()
    {

        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Liquipedia:Upcoming_and_ongoing_matches');


        $team1 = $crawler->filter('.infobox_matches_content .team-left .team-template-team2-short')->each(function ($node) {
            $title = $node->extract('data-highlightingclass');
            $title = str_replace(" (page does not exist)", "", $title);
            return $title[0];

        });

        $team2 = $crawler->filter('.infobox_matches_content .team-right .team-template-team-short')->each(function ($node) {
            $title = $node->extract('data-highlightingclass');
            $title = str_replace(" (page does not exist)", "", $title);
            return $title[0];

        });


        $tTitle = $crawler->filter('.infobox_matches_content  tr:nth-child(2) .match-filler div div a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });


        $start_time = $crawler->filter('.infobox_matches_content .datetime')->each(function ($node) {

            $start_time = $node->text();


            $start_time = explode(" ", $start_time);

            $month = date("m", strtotime($start_time[0]));
            $day = str_replace(",", "", $start_time[1]);
            $start_time[1];
            $year = $start_time[2];
            $time = date('H:i', strtotime($start_time[4]));

            // $timestamp = strtotime($day . $month . $year . $time);

            $datetime = "$year-$month-$day $time";


            $utc_date = DateTime::createFromFormat(
                'Y-m-d H:i',
                $datetime,
                new DateTimeZone('UTC')
            );

            $nyc_date = $utc_date;
            $nyc_date->setTimeZone(new DateTimeZone('Europe/Stockholm'));
            return $nyc_date->format("Y-m-d H:i");

        });

        // First lets sort tbd1 and tbd2 and tournaments together to an new array collection
        function tournamentMatchesArray($team1, $team2, $tTitle, $start_time)
        {
            foreach ($team1 as $key => $tbd) {


                $result[] = array(
                    'team1' => $team1[$key],
                    'team2' => $team2[$key],
                    'tournament' => $tTitle[$key],
                    'start_time' => $start_time[$key]);
            }
            return $result;
        }



        // Call the functions to make the collections
        $removeTBD = tournamentMatchesArray($team1, $team2, $tTitle, $start_time);

        // Remove all TBD teams from array and tournaments -_-
        foreach ($removeTBD as $key => $array) {
            if ($array['team1'] == 'tbd') {
                unset($removeTBD[$key]);
            }
            if ($array['team2'] == 'tbd') {
                unset($removeTBD[$key]);
            }
        }


        // Reanrange the order :)
        $mergeArray = array_values($removeTBD);


        $i = 0;
        foreach ($mergeArray as $tMatches) {


            $tournament = Tournament::Where('title', $tMatches['tournament'])->first();
            if ($tournament['title']) {

                $team1 = Team::Where('name', '=', $tMatches['team1'])->first();
                $team2 = Team::Where('name', '=', $tMatches['team2'])->first();

                if ($team1 && $team2) {

                    // check if match exists !
                    $matchExists = Match::Where('team1_id', '=', $team1->id)->where('team2_id', '=', $team2->id)->where('start_time', '=', $tMatches['start_time'])->where('tournament_id', '=', $tournament['id'])->first();
                    if (!$matchExists) {


                        $i++;

                        $match = new Match;

                        $match->team1_id = $team1->id;
                        $match->team2_id = $team2->id;
                        $match->start_time = $tMatches['start_time'];
                        $match->tournament_id = $tournament['id'];

                        $match->save();
                    }

                }
            }
        }

        dump($i . 'Matches added');
    }
}
