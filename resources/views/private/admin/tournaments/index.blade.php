@extends('layouts.app')

@section('content')
    <section>
        <header>
            <h1>Admin -> Turneringer</h1>
            <h3>Liste over dota 2 Turneringer</h3>
            <p><a href="{{route('admin.dashboard')}}">>>Dashboard</a></p>
            <p><a href="{{route('admin.tournaments.create')}}">Opret ny dota 2 Turnering</a></p>
        </header>



        {!! Form::open(['route' => ['admin.tournaments.destroy.all'], 'method' => 'delete' ]) !!}
        <button type="submit" class="btn-no-style btn-delete"
                onclick='return confirm("Er du sikker på du vil slette alle turneringer ?")'>
            <b>Slet alle turneringer!</b>
        </button>
        {!! Form::close() !!}

        <ul>
            <li>
                <a href="{{route('crawlTournamentsPremierEvents')}}">Crawl Premier Events</a>
            </li>
            <li>
                <a href="{{route('crawlTournamentsMajorEvents')}}">Crawl Major Events</a>
            </li>
            <li>
                <a href="{{route('crawlTournamentsQualifiers')}}">Crawl Qualifiers Events</a>
            </li>
            <li>
                <a href="{{route('crawlTournamentsMinorEvents')}}">Crawl Minor Events</a>
            </li>
            <li>
                <a href="{{route('crawlTournamentsMatches')}}">Crawl Turnaments matches</a>
            </li>
        </ul>

        <article>
            @foreach($tournaments as $tournament)
                <div>

                    @if(isset($tournament->logo))
                        <img height="40" src="{{asset(env('STORAGE_DISK_PATH')."/tournaments/".$tournament->logo)}}">
                        @endif
                    {{$tournament->name}}

                    <a href='{{route('admin.tournaments.edit', $tournament->id)}}'>Rediger</a>
                    {!! Form::open(['route' => ['admin.tournaments.destroy', $tournament->id], 'method' => 'delete' ]) !!}
                    <button type="submit" class="btn-no-style btn-delete"
                            onclick='return confirm("Er du sikker på du vil slette {{ $tournament->name }} ?")'>
                        Slet
                    </button>
                    {!! Form::close() !!}

                </div>
            @endforeach

        </article>
    </section>

@endsection

