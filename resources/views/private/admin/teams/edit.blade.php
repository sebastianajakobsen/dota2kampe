@extends('layouts.app')

@section('content')

    <section>
        <header>
            <h1>Admin -> Hold</h1>
            <h3>Rediger Hold ( {{$team->name}} )</h3>
            <p><a href="{{route('admin.teams.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
            'method' => 'PATCH',
            'route' => array('admin.teams.update', $team->id),
            'role' => 'form',
            'files' => true,
            'multiple' => true,
        ]) !!}
                @include('private.admin.teams.form', ['submitButton' => 'Rediger'])

            {!! Form::close() !!}
        </article>
    </section>

@endsection