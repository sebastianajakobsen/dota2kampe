@extends('layouts.app')

@section('content')

    <h1>Opret bruger</h1>
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}



        <input type="text" name="brugernavn" value="{{ old('username') }}"  Placeholder="Brugernavn"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Brugernavn'" required>

        @if ($errors->has('brugernavn'))
            <strong>{{ $errors->first('brugernavn') }}</strong>
        @endif
<br />



        <input type="email" name="email" value="{{ old('email') }}" Placeholder="Email"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" required>

        @if ($errors->has('email'))

            <strong>{{ $errors->first('email') }}</strong>

        @endif
        <br />

        <input type="password" name="kodeord" Placeholder="Kodeord"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Kodeord'" required>

        @if ($errors->has('kodeord'))

            <strong>{{ $errors->first('kodeord') }}</strong>

        @endif
<br />

        <input type="password" name="kodeord_confirmation" Placeholder="Bekræft kodeord"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Bekræft kodeord'" required>

        <br />

        @if ($errors->has('kodeord_confirmation'))

            <strong>{{ $errors->first('kodeord_confirmation') }} </strong>

        @endif

        {{--@if ($errors->has('recaptcha'))--}}

                                        {{--<strong>{{ $errors->first('recaptcha') }} </strong>--}}

        {{--@endif--}}
{{--<br />--}}
        {{--{!! Recaptcha::render() !!}--}}


        <br />
        <button type="submit" class="btn btn-primary">
            Opret konto
        </button>

    </form>

@endsection
