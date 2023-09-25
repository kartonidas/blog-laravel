@extends('panel.layout')

@section('content')
    <h2>{{ $data['title'] }}</h2>
    <div>
        {{ $data['content'] }}
    </div>
        
    @if(!empty($data['images']))
        <h5>Lista plików</h5>
        <ul class="list-unstyled">
            @foreach($data['images'] as $img)
                <li>{{ $img['orig_filename'] }}</li>
            @endforeach
        </ul>
    @endif
@endsection