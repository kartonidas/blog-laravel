@extends('emails.template')

@section('content')
    <div margin-bottom: 10px;>
        <p>
            Aby zresetować hasło prosimy o kliknięcie w poniższy link.
        </p>
        <p>
            Token: {{ $token }}
        </p>
    </div>
        
    <div style="text-align: left; margin-top: 10px; margin-bottom: 10px">
        <a href="{{ $url }}">
            Reset hasła
        </a>
    </div>
@endsection