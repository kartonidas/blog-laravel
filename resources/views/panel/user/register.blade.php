@extends('panel.layout')

@section('content')
    <h2>Zarejestruj się</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="loginName">Imię</label>
            <input type="text" name="name" class="form-control" id="loginName" value="{{ old('name', '') }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="loginEmail">Adres e-mail</label>
            <input type="text" name="email" class="form-control" id="loginEmail" value="{{ old('email', '') }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="loginPassword">Hasło</label>
            <input type="password" name="password" class="form-control" id="loginPassword">
        </div>
        <button type="submit" class="btn btn-primary">Zarejestruj się</button>
        @csrf
    </form>
@endsection