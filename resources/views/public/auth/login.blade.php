@extends('layouts.app')
@section('content')
    <h1>Log på</h1>
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <input type="email" name="email" value="{{ old('email') }}"  Placeholder="Email"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'" required>

        @if ($errors->has('email'))
            <span>
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
        <br />


        <input type="password" name="kodeord"   Placeholder="Kodeord"
               onfocus="this.placeholder = ''" onblur="this.placeholder = 'Kodeord'" required>

        @if ($errors->has('kodeord'))

            <strong>{{ $errors->first('kodeord') }}</strong>

        @endif

        <br />
        @if ($errors->has('g-recaptcha-response'))
            <strong>Recaptcha skal udfyldes korrekt</strong>
        @endif

        @if(Cookie::get('loginAttempt') > 3)
            {!! Recaptcha::render() !!}
        @endif

                <button type="submit" class="btn btn-primary">
                    Log på
                </button>

                <a href="{{ route('password.reset') }}">
                    Glemt dit kodeord?
                </a>

    </form>
@endsection
