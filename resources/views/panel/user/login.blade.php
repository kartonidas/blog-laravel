@extends('panel.layout')

@section('content')
    <h2>Zaloguj się</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="loginEmail">Adres e-mail</label>
            <input type="text" name="email" class="form-control" id="loginEmail" value="{{ old('email', '') }}">
        </div>
            
        <div class="form-group">
            <label for="loginPassword">Hasło</label>
            <input type="password" name="password" class="form-control" id="loginPassword">
        </div>
            
        <div class="mb-3">
            <small><a href="{{ route('user.forgot_password') }}">Nie pamiętasz hasła?</a></small>
        </div>
            
        <button type="submit" class="btn btn-primary">Zaloguj się</button>
        @csrf
    </form>
@endsection