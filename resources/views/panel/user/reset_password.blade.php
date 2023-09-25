@extends('panel.layout')

@section('content')
    <h2>Ustaw nowe hasło</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="loginPassword">Hasło</label>
            <input type="password" name="password" class="form-control" id="loginPassword">
        </div>
        <button type="submit" class="btn btn-primary">Ustaw nowe hasło</button>
        @csrf
    </form>
@endsection