@extends('panel.layout')

@section('content')
    <h2>Aktywacja</h2>
    
    @if($status_code == 200)
        Konto zostało aktywowane.
        <br/>
        <a href="{{ route('user.login') }}">Zaloguj się</a>
    @else
        <div>
            Nie udało się aktywować konta, sprawdź adres URL.
        </div>
    @endif
@endsection