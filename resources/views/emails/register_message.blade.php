@extends('emails.template')

@section('content')
    <div margin-bottom: 10px;>
        <p>
            Dziękujemy za rejestrację w naszym serwisie.
            Aby dokończyć proces rejestracji, prosimy o kliknięcie w poniższy link.
        </p>
        <p>
            Token: {{ $token }}
        </p>
    </div>
        
    <div style="text-align: left; margin-top: 10px; margin-bottom: 10px">
        <a href="{{ $url }}">
            Potwierdź rejestrację
        </a>
    </div>
@endsection