@extends('panel.layout')

@section('content')
    <h2>Przypomnij hasło</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="loginEmail">Adres e-mail</label>
            <input type="text" name="email" class="form-control" id="loginEmail" value="{{ old('email', '') }}">
        </div>
        <button type="submit" class="btn btn-primary">Przypomnij hasło</button>
        @csrf
    </form>
@endsection