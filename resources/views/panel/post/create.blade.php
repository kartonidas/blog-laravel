@extends('panel.layout')

@section('content')
    <h2>Nowy wpis</h2>
        
    <form method="post" class="mt-2" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="postTitle">Tytuł</label>
            <input type="text" name="title" class="form-control" id="postTitle" value="{{ old('title', '') }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="loginEmail">Treść</label>
            <textarea name="content" class="form-control">{{ old('content') }}</textarea>
        </div>
            
        <div class="form-group mb-3">
            <input type="file" class="form-control" name="file_1">
        </div>
        <div class="form-group mb-3">
            <input type="file" class="form-control" name="file_2">
        </div>
        <div class="form-group mb-3">
            <input type="file" class="form-control" name="file_3">
        </div>
            
        <button type="submit" class="btn btn-primary">Utwórz</button>
        @csrf
    </form>
@endsection