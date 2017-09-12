@extends('layouts.app')

@section('content')
    <section>
        <header>
            <h1>Admin -> Hold</h1>
            <h3>Liste over dota 2 hold</h3>
            <p><a href="{{route('admin.dashboard')}}">>>Dashboard</a></p>
            <p><a href="{{route('admin.teams.create')}}">Opret et nyt dota 2 hold</a></p>
        </header>

        {!! Form::open(['route' => ['admin.teams.destroy.all'], 'method' => 'delete' ]) !!}
        <button type="submit" class="btn-no-style btn-delete"
                onclick='return confirm("Er du sikker på du vil slette alle hold ?")'>
            <b>Slet alle hold!</b>
        </button>
        {!! Form::close() !!}

        <ul>
            <li>
                <a href="{{route('crawlTeams')}}">Hent Hold</a>
            </li>

        </ul>

        <article>
            @foreach($teams as $team)
                <div>
                    @if(isset($team->country_id))
                        <span class="flag-icon flag-icon-{{strtolower($team->country->iso)}}"></span>
                    @endif
                    <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/teams/".$team->logo)}}">
                    {{$team->name}}

                    <a href='{{route('admin.teams.edit', $team->id)}}'>Rediger</a>
                    {!! Form::open(['route' => ['admin.teams.destroy', $team->id], 'method' => 'delete', 'class' => 'form-delete' ]) !!}
                    <button type="submit" class="btn-no-style btn-delete"
                            onclick='return confirm("Er du sikker på du vil slette {{ $team->name }} ?")'>
                        Slet
                    </button>
                    {!! Form::close() !!}


                    <ul>

                        @foreach($team->players as $player)
                            <li>
                                <img height="25" src="{{asset(env('STORAGE_DISK_PATH')."/players/".$player->image)}}">{{$player->name}} [ {{$player->solo_mmr}} ]
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

        </article>
    </section>

@endsection

