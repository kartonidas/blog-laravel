@if ($errors->any())
    <div class="alert alert-danger text-start {{ $margin ?? "" }}">
        <ul class="list-unstyled mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (request()->session()->has('status'))
    <div class="alert alert-success text-start {{ $margin ?? "" }}">
        {{ request()->session()->get('status') }}
    </div>
@endif