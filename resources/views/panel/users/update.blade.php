@extends('panel.layout')

@section('content')
    <h2>Aktualizuj użytkownika</h2>
        
    <form method="post" class="mt-2">
        <div class="form-group mb-3">
            <label for="userName">Nazwa</label>
            <input type="text" name="name" class="form-control" id="userName" value="{{ old('name', $data['name']) }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="userEmail">E-mail</label>
            <input type="text" name="email" class="form-control" id="userEmail" value="{{ old('email', $data['email']) }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="userRole">Rola</label>
            <select class="form-control" name="user_role" id="userRole">
                <option value="user" @if(old('user_role', $data['user_role']) == 'user'){{ 'selected' }}@endif>User</option>
                <option value="editor" @if(old('user_role', $data['user_role'])  == 'editor'){{ 'selected' }}@endif>Editor</option>
                <option value="admin" @if(old('user_role', $data['user_role']) == 'admin'){{ 'selected' }}@endif>Admin</option>
            </select>
        </div>
            
        <div class="form-group mb-3">
            <div class="form-check">
                <input class="form-check-input" name='change_password' type="checkbox" value="1" id="userChangePassword" onclick="document.getElementById('userPasswordContainer').classList.toggle('d-none');" @if(old('change_password')){{ 'checked' }}@endif>
                <label class="form-check-label" for="userChangePassword">
                    Zmień hasło
                </label>
              </div>
        </div>
            
        <div class="form-group mb-3 @if(!old('change_password')){{ 'd-none' }}@endif" id="userPasswordContainer">
            <label for="userPassword">Hasło</label>
            <input type="password" name="password" class="form-control" id="userPassword">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        @csrf
    </form>
@endsection