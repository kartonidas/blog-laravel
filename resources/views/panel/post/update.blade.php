@extends('panel.layout')

@section('content')
    <h2>Aktualizuj wpis</h2>
        
    <form method="post" class="mt-2" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="postTitle">Tytuł</label>
            <input type="text" name="title" class="form-control" id="postTitle" value="{{ old('title', $data['title']) }}">
        </div>
            
        <div class="form-group mb-3">
            <label for="loginEmail">Treść</label>
            <textarea name="content" class="form-control">{{ old('content', $data['content']) }}</textarea>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <h5>Lista plików</h5>
                @if(!empty($data['images']))
                    <table class="table">
                        @foreach($data['images'] as $img)
                            <tr>
                                <td>{{ $img['orig_filename'] }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#removeModal{{ $img['id'] }}" class="btn btn-danger">Usuń</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    Brak plików.
                @endif
            </div>
            <div class="col-lg-6">
                <div class="form-group mb-3">
                    <input type="file" class="form-control" name="file_1">
                </div>
                <div class="form-group mb-3">
                    <input type="file" class="form-control" name="file_2">
                </div>
                <div class="form-group mb-3">
                    <input type="file" class="form-control" name="file_3">
                </div>
            </div>
        </div>
            
        <button type="submit" class="btn btn-primary">Zapisz</button>
        @csrf
    </form>
        
    @if(!empty($data['images']))
        @foreach($data['images'] as $img)
            <div class="modal fade" id="removeModal{{ $img['id'] }}" tabindex="-1" aria-labelledby="removeModal{{ $img['id'] }}Label" aria-hidden="true">
                <div class="modal-dialog text-start">
                    <form method="post" action="{{ route('post.delete.photo', [$img['post_id'], $img['id']]) }}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="removeModal{{ $img['id'] }}Label">Usuń zdjęcie</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Usunąć wybrane zdjęcie?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                <button type="submit" class="btn btn-primary">Usuń</button>
                            </div>
                        </div>
                        @csrf
                    </form>
                </div>
            </div>
        @endforeach
    @endif
@endsection