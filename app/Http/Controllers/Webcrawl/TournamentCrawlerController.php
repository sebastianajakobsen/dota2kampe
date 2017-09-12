<?php

namespace App\Http\Controllers\Webcrawl;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Tournament;

use Goutte\Client;

use Illuminate\Support\Facades\Storage;



class TournamentCrawlerController extends Controller
{
    //

    public function crawlTournamentsPremierEvents()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Premier_Tournaments');
        $name = $crawler->filter('table:first-of-type tr td:nth-child(3)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        $title = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });


        $wiki = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $href = $node->extract('href');
            return 'http://wiki.teamliquid.net' . $href[0];
        });


        $logo = $crawler->filter('table:first-of-type tr td:nth-child(4) img')->each(function ($node) {
            $src = $node->extract('src');
            return 'http://wiki.teamliquid.net' . $src[0];
        });

        $start_date = $crawler->filter('table:first-of-type tr td:first-child span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $end_date = $crawler->filter('table:first-of-type tr td:nth-child(2) span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        // Merge arrays functions
        function mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date)
        {
            foreach ($name as $key => $name) {
                $result[] = array('name' => $name, 'logo' => $logo[$key], 'wiki' => $wiki[$key], 'title' => $title[$key], 'start_date' => $start_date[$key], 'end_date' => $end_date[$key]);
            }
            return $result;
        }

        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date);


        $i = 0;
        foreach ($mergeArray as $tournamentInfo) {

            $tjek = Tournament::Where('title', '=', $tournamentInfo['title'])->get();
            if (!$tjek->count()) {
                $i++;

                $newTournament = new Tournament;
                $newTournament->name = $tournamentInfo['name'];
                $newTournament->title = $tournamentInfo['title'];
                $newTournament->tier = "Premier";
                $newTournament->slug = str_slug($tournamentInfo['name'], '_');
                $newTournament->wiki = $tournamentInfo['wiki'];
                $newTournament->start_date = $tournamentInfo['start_date'];
                $newTournament->end_date = $tournamentInfo['end_date'];


                $filenameIMG = md5($tournamentInfo['name'] . microtime()) . ".png";

                $file = @file_get_contents($tournamentInfo['logo']);
                Storage::disk(env('STORAGE_DISK_DRIVER'))->put('tournaments/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);


                $newTournament->logo = $filenameIMG;

                $newTournament->save();
            }
        }
        dump($i . 'Tournaments Premier events added');

    }


    public function crawlTournamentsMajorEvents()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Major_Tournaments');
        $name = $crawler->filter('table:first-of-type tr td:nth-child(3)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        $title = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });


        $wiki = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $href = $node->extract('href');
            return 'http://wiki.teamliquid.net' . $href[0];
        });


        $logo = $crawler->filter('table:first-of-type tr td:nth-child(4) img')->each(function ($node) {
            $src = $node->extract('src');
            return 'http://wiki.teamliquid.net' . $src[0];
        });

        $start_date = $crawler->filter('table:first-of-type tr td:first-child span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $end_date = $crawler->filter('table:first-of-type tr td:nth-child(2) span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        // Merge arrays functions
        function mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date)
        {
            foreach ($name as $key => $name) {
                $result[] = array('name' => $name, 'logo' => $logo[$key], 'wiki' => $wiki[$key], 'title' => $title[$key], 'start_date' => $start_date[$key], 'end_date' => $end_date[$key]);
            }
            return $result;
        }

        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date);

        $i = 0;
        foreach ($mergeArray as $tournamentInfo) {

            $tjek = Tournament::Where('title', '=', $tournamentInfo['title'])->first();
            if (!$tjek) {
                $i++;

                $newTournament = new Tournament;
                $newTournament->name = $tournamentInfo['name'];
                $newTournament->title = $tournamentInfo['title'];
                $newTournament->tier = "Major";
                $newTournament->slug = str_slug($tournamentInfo['name'], '_');
                $newTournament->wiki = $tournamentInfo['wiki'];
                $newTournament->start_date = $tournamentInfo['start_date'];
                $newTournament->end_date = $tournamentInfo['end_date'];


                $filenameIMG = md5($tournamentInfo['name'] . microtime()) . ".png";

                $file = @file_get_contents($tournamentInfo['logo']);
                Storage::disk(env('STORAGE_DISK_DRIVER'))->put('tournaments/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);


                $newTournament->logo = $filenameIMG;


                $newTournament->save();
            }
        }
        dump($i . ' Tournaments Majors events added');

    }


    public function crawlTournamentsMinorEvents()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Minor_Tournaments');
        $name = $crawler->filter('table:first-of-type tr td:nth-child(3)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        $title = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });


        $wiki = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $href = $node->extract('href');
            return 'http://wiki.teamliquid.net' . $href[0];
        });


        $logo = $crawler->filter('table:first-of-type tr td:nth-child(4) img')->each(function ($node) {
            $src = $node->extract('src');
            return 'http://wiki.teamliquid.net' . $src[0];
        });

        dd($logo);


        $start_date = $crawler->filter('table:first-of-type tr td:first-child span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $end_date = $crawler->filter('table:first-of-type tr td:nth-child(2) span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        // Merge arrays functions
        function mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date)
        {
            foreach ($name as $key => $name) {
                $result[] = array('name' => $name, 'logo' => $logo[$key], 'wiki' => $wiki[$key], 'title' => $title[$key], 'start_date' => $start_date[$key], 'end_date' => $end_date[$key]);
            }
            return $result;
        }


        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date);


        $i = 0;
        foreach ($mergeArray as $tournamentInfo) {

            $tjek = Tournament::Where('title', '=', $tournamentInfo['title'])->first();
            if (!$tjek) {
                $i++;

                $newTournament = new Tournament;
                $newTournament->name = $tournamentInfo['name'];
                $newTournament->title = $tournamentInfo['title'];
                $newTournament->tier = "Minor";
                $newTournament->slug = str_slug($tournamentInfo['name'], '_');
                $newTournament->wiki = $tournamentInfo['wiki'];
                $newTournament->start_date = $tournamentInfo['start_date'];
                $newTournament->end_date = $tournamentInfo['end_date'];

                if (isset($tournamentInfo['logo'])) {
                    $filenameIMG = md5($tournamentInfo['name'] . microtime()) . ".png";
                    $file = @file_get_contents($tournamentInfo['logo']);
                    Storage::disk(env('STORAGE_DISK_DRIVER'))->put('tournaments/' . $filenameIMG, $file,
                        [
                            'visibility' => 'public',
                            'CacheControl' => 'max-age=31536000'
                        ]);


                    $newTournament->logo = $filenameIMG;
                }

                $newTournament->save();
            }
        }
        dump($i . 'Tournaments Minors added');

    }


    public function crawlTournamentsQualifiers()
    {
        // Create a Goutte Client instance
        $client = new Client();

        // Go to the wiki.teamliquid.net team page
        $crawler = $client->request('GET', 'http://wiki.teamliquid.net/dota2/Qualifier_Tournaments');
        $name = $crawler->filter('table:first-of-type tr td:nth-child(3)')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $title = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $title = $node->extract('title');
            return $title[0];
        });


        $wiki = $crawler->filter('table:first-of-type tr td:nth-child(3) a')->each(function ($node) {
            $href = $node->extract('href');
            return 'http://wiki.teamliquid.net' . $href[0];
        });


        $logo = $crawler->filter('table:first-of-type tr td:nth-child(4) img')->each(function ($node) {
            $src = $node->extract('src');
            return 'http://wiki.teamliquid.net' . $src[0];
        });

        $start_date = $crawler->filter('table:first-of-type tr td:first-child span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });

        $end_date = $crawler->filter('table:first-of-type tr td:nth-child(2) span')->each(function ($node) {
            return trim(preg_replace('/\s+/', ' ', $node->text()));
        });


        // Merge arrays functions
        function mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date)
        {
            foreach ($name as $key => $name) {
                $result[] = array('name' => $name, 'logo' => $logo[$key], 'wiki' => $wiki[$key], 'title' => $title[$key], 'start_date' => $start_date[$key], 'end_date' => $end_date[$key]);
            }
            return $result;
        }

        // Use the merge arrays functions to merge our arrays together to a new one
        $mergeArray = mergeArrays($name, $logo, $wiki, $title, $start_date, $end_date);

        $i = 0;
        foreach ($mergeArray as $tournamentInfo) {

            $tjek = Tournament::Where('title', '=', $tournamentInfo['title'])->first();
            if (!$tjek) {
                $i++;

                $newTournament = new Tournament;
                $newTournament->name = $tournamentInfo['name'];
                $newTournament->title = $tournamentInfo['title'];
                $newTournament->tier = "Qualifier";
                $newTournament->slug = str_slug($tournamentInfo['name'], '_');
                $newTournament->wiki = $tournamentInfo['wiki'];
                $newTournament->start_date = $tournamentInfo['start_date'];
                $newTournament->end_date = $tournamentInfo['end_date'];


                $filenameIMG = md5($tournamentInfo['name'] . microtime()) . ".png";

                $file = @file_get_contents($tournamentInfo['logo']);
                Storage::disk(env('STORAGE_DISK_DRIVER'))->put('tournaments/' . $filenameIMG, $file,
                    [
                        'visibility' => 'public',
                        'CacheControl' => 'max-age=31536000'
                    ]);


                $newTournament->logo = $filenameIMG;


                $newTournament->save();
            }
        }
        dump($i . 'Tournaments Qualifiers added');

    }

}
