@extends('layouts.app')

@section('content')

    <section>


        <header>
            <h1>Admin -> Turnering</h1>

            <h3>Opret ny Turnering</h3>

            <p><a href="{{route('admin.tournaments.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
 'method' => 'POST',
 'route' => array('admin.tournaments.store'),
 'role' => 'form',
           'files' => true,
            'multiple' => true,
]) !!}

            @include('private.admin.tournaments.form', ['submitButton' => 'Opret'])
            {!! Form::close() !!}
        </article>
    </section>

@endsection

