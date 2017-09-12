@extends('layouts.app')

@section('content')

    <section>
        <header>
            <h1>Admin -> Turnering</h1>
            <h3>Rediger Turnering ( {{$tournament->name}} )</h3>
            <p><a href="{{route('admin.tournaments.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
            'method' => 'PATCH',
            'route' => array('admin.tournaments.update', $tournament->id),
            'role' => 'form',
            'files' => true,
            'multiple' => true,
        ]) !!}
                @include('private.admin.tournaments.form', ['submitButton' => 'Rediger'])

            {!! Form::close() !!}
        </article>
    </section>

@endsection