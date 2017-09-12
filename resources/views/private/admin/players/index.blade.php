@extends('layouts.app')

@section('content')

    <section>
        <header>
            <h1>Admin -> Hold</h1>
            <h3>Liste over dota 2 spiller</h3>
            <p><a href="{{route('admin.dashboard')}}">>>Dashboard</a></p>
            <p><a href="{{route('admin.players.create')}}">Opret ny dota 2 spiller</a></p>
        </header>

        {!! Form::open(['route' => ['admin.players.destroy.all'], 'method' => 'delete' ]) !!}
        <button type="submit" class="btn-no-style btn-delete"
                onclick='return confirm("Er du sikker på du vil slette alle spiller ?")'>
            <b>Slet alle spiller!</b>
        </button>
        {!! Form::close() !!}

        <ul>
            <li>
                <a href="{{route('crawlPlayers')}}">Hent Spillere</a>
            </li>
            <li>
                <a href="{{route('crawlPlayersImage')}}">Opdatere Spiller Billede</a>
            </li>
            <li>
                <a href="{{route('crawlPlayersMMR')}}">Opdater Spiller MMR</a>
            </li>
        </ul>

        <article>
            @foreach($players as $player)
                <div>
                    <ul>
                        <li>
                            @if(isset($player->country_id))
                                <span class="flag-icon flag-icon-{{strtolower($player->country->iso)}}"></span>
                            @endif
                    <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/players/".$player->image)}}">
                    {{$player->name}}     <a href='{{route('admin.players.edit', $player->id)}}'>Rediger</a>
                    {!! Form::open(['route' => ['admin.players.destroy', $player->id], 'method' => 'delete', 'class' => 'form-delete']) !!}
                    <button type="submit" class="btn-no-style btn-delete"
                            onclick='return confirm("Er du sikker på du vil slette {{ $player->name }} ?")'>
                        Slet
                    </button>
                    {!! Form::close() !!}

                        @if(isset($player->team->name))
                                <img height="25" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$player->team->logo)}}">{{$player->team->name}}
                        @endif
                        </li>
                    </ul>
                </div>
            @endforeach

        </article>
    </section>
@endsection

