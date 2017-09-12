@extends('layouts.app')

@section('content')

    <section>


        <header>
            <h1>Admin -> Spiller</h1>
            <h3>Opret ny spiller</h3>
            <p><a href="{{route('admin.players.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
 'method' => 'POST',
 'route' => array('admin.players.store'),
 'files' => true
]) !!}

            @include('private.admin.players.form', ['submitButton' => 'Opret'])
            {!! Form::close() !!}
        </article>
    </section>

@endsection

