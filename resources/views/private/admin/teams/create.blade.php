@extends('layouts.app')

@section('content')

    <section>


        <header>
            <h1>Admin -> Hold</h1>

            <h3>Opret nyt hold</h3>

            <p><a href="{{route('admin.teams.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
 'method' => 'POST',
 'route' => array('admin.teams.store'),
 'role' => 'form',
           'files' => true,
            'multiple' => true,
]) !!}

            @include('private.admin.teams.form', ['submitButton' => 'Opret'])
            {!! Form::close() !!}
        </article>
    </section>

@endsection

