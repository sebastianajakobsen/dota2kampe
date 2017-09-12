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

        $matchRow = $crawler->filter('.infobox_matches_content')->each(function ($node) {
            return $node->html();
        });

        $i = 0;
        foreach ($matchRow as $key => $mRow) {
            if (strpos($mRow, 'TBD') !== false) {
                $i++;
                echo $i . ' true ';
                unset($matchRow[$key]);
            }

        }

        $matchRow = array_values($matchRow);
        dump('we need to fix this shit');
        dd($matchRow);

		// can we end this shit please

        $team1 = $crawler->filter('.infobox_matches_content .team-left .team-template-text a')->each(function ($node) {
            $title = $node->extract('title');
            $title = str_replace(" (page does not exist)", "", $title);
            return $title[0];
        });


        $team2 = $crawler->filter('.infobox_matches_content .team-right .team-template-team-short .team-template-text a')->each(function ($node) {
            $title = $node->extract('title');
            $title = str_replace(" (page does not exist)", "", $title);
            return $title[0];

        });


        // GET TBD TEAMS so you can remove them after and only have the tournaments with teams left.
        // Else they crawler is going to unsort tournament so they dont match to the matches and teams :(
        // example = tournaments results 62 but only 32 teams1 & teams 2 BC the others are TBD so we have to remove alle the tournaments that we are not going to use
        // so we have 32 team1 & team2 and 32 tournaments.... :D
        $tbd1 = $crawler->filter('.infobox_matches_content .team-left .team-template-team2-short .team-template-text ')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $tbd2 = $crawler->filter('.infobox_matches_content .team-right .team-template-team-short .team-template-text ')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
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
        function tournamentTBDarray($tbd1, $tbd2, $tTitle)
        {
            foreach ($tbd1 as $key => $tbd) {


                $result[] = array('tbd1' => $tbd, 'tbd2' => $tbd2[$key], 'tournament' => $tTitle[$key]);
            }
            return $result;
        }


        // Call the functions to make the collections
        $removeTBD = tournamentTBDarray($tbd1, $tbd2, $tTitle);

        // Remove all TBD teams from array and tournaments -_-
        foreach ($removeTBD as $key => $array) {
            if ($array['tbd1'] == 'TBD') {
                unset($removeTBD[$key]);
            }
            if ($array['tbd2'] == 'TBD') {
                unset($removeTBD[$key]);
            }
        }


        // Reanrange the order :)
        $mergeEmpty = array_values($removeTBD);


        // make a new array only with turnaments left without teams :)
        $tTitle = array();
        foreach ($mergeEmpty as $t) {
            $tTitle[] = $t['tournament'];
        }


        $team1 = array_values($team1);
        dump($team1, $team2);
        dd('team size dosnt match');

        $team2 = array_values($team2);
        $start_time = array_values($start_time);
        $tTitle = array_values($tTitle);

        // make a new array only with turnaments left without teams :)

        // merge everything together again. Now the tournaments and team should match!! :D:D:D:D:D
        function mergeArrays($team1, $team2, $start_time, $tTitle)
        {
            foreach ($team1 as $key => $name) {
                $result[] = array('team1' => $name,
                    'team2' => $team2[$key],
                    'format' => 'BO2',
                    'tournamentTitle' => $tTitle[$key],
                    'start_time' => $start_time[$key]
                );
            }
            return $result;
        }


        $mergeArray = mergeArrays($team1, $team2, $start_time, $tTitle);

        dd($mergeArray);
        $i = 0;
        foreach ($mergeArray as $tMatches) {

            $t = Tournament::Where('title', '=', $tMatches['tournamentTitle'])->first();

            if ($t) {

                $team1 = Team::Where('title', '=', $tMatches['team1'])->first();
                $team2 = Team::Where('title', '=', $tMatches['team2'])->first();

                if ($team1 && $team2) {


                    // check if match exists !
                    $matchExists = Match::Where('team1_id', '=', $team1->id)->where('team2_id', '=', $team2->id)->where('start_time', '=', $tMatches['start_time'])->where('tournament_id', '=', $t->id)->first();
                    if (!$matchExists) {


                        $i++;

                        $match = new Match;

                        $match->team1_id = $team1->id;
                        $match->team2_id = $team2->id;
                        $match->start_time = $tMatches['start_time'];
                        $match->tournament_id = $t->id;

                        $match->save();
                    }

                }
            }
        }

        dump($i . 'Matches added');
    }
}
