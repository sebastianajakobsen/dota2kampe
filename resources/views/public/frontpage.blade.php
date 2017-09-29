@extends('layouts.app')

@section('content')

@foreach($matches as $match)
    <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$match->team1->logo)}}">{{$match->team1->name}} vs {{$match->team2->name}} <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$match->team2->logo)}}"> {{$match->start_time}}
    <br />
    @endforeach
<br /><br />
        @foreach($tournaments as $tournament)
            <div>
                {{$tournament->tier}}  <img height="20" src="{{asset(env('STORAGE_DISK_PATH')."/tournaments/".$tournament->logo)}}"> {{$tournament->name}}
            </div>
        @endforeach
            <br /> <br />
        @foreach($teams as $team)
            <div>
                <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$team->logo)}}">
                {{$team->name}}
                <ul>
                    @foreach($team->players as $player)
                    <li>
                        <img height="25" src="{{asset(env('STORAGE_DISK_PATH')."/players/".$player->image)}}">{{$player->name}} [ {{$player->solo_mmr}} ]
                    </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

@endsection