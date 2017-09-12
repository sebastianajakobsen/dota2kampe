@extends('layouts.app')

@section('content')
    <section>
        <header>
            <h1>Velkommen admin {{ Auth::user()->username }} </h1>
            <h3>Admin dashboard</h3>
        </header>
        <nav>
            <nav>
                <ul>
                    <li><a href="{{route('admin.tournaments.index')}}">Turneringer &#187;</a></li>
                    <li><a href="{{route('admin.players.index')}}">Spiller &#187;</a></li>
                    <li><a href="{{route('admin.teams.index')}}">Hold &#187;</a></li>
                </ul>
            </nav>
        </nav>
    </section>
@endsection