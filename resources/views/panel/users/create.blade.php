@extends('panel.layout')

@section('content')
    <h2>Nowy użytkownik</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="userName">Nazwa</label>
            <input type="text" name="name" class="form-control" id="userName" value="{{ old('name') }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="userEmail">E-mail</label>
            <input type="text" name="email" class="form-control" id="userEmail" value="{{ old('email') }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="userRole">Rola</label>
            <select class="form-control" name="user_role" id="userRole">
                <option value="user" @if(old('user_role') == 'user'){{ 'selected' }}@endif>User</option>
                <option value="editor" @if(old('user_role')  == 'editor'){{ 'selected' }}@endif>Editor</option>
                <option value="admin" @if(old('user_role') == 'admin'){{ 'selected' }}@endif>Admin</option>
            </select>
        </div>
            
        <div class="form-group mb-3">
            <label for="userPassword">Hasło</label>
            <input type="password" name="password" class="form-control" id="userPassword">
        </div>
        <button type="submit" class="btn btn-primary">Utwórz</button>
        @csrf
    </form>
@endsection