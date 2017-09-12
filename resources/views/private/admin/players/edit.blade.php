@extends('layouts.app')

@section('content')

    <section>
        <header>
            <h1>Admin -> spiller</h1>
            <h3>Rediger spiller ( {{$player->name}} )</h3>
            <p><a href="{{route('admin.players.index')}}">>>Tilbage</a></p>
        </header>
        <article>
            {!! Form::open([
            'method' => 'PATCH',
            'route' => array('admin.players.update', $player->id),
            'files' => true
        ]) !!}
                @include('private.admin.players.form', ['submitButton' => 'Rediger'])

            {!! Form::close() !!}
        </article>
    </section>

@endsection