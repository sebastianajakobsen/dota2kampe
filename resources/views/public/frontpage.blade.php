@extends('layouts.app')

@section('content')


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