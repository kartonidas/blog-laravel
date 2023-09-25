<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog</title>
            
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="py-2 bg-light border-bottom">
            <div class="container d-flex flex-wrap">
                <ul class="nav me-auto">
                    <li class="nav-item"><a href="/" class="nav-link link-dark px-2 active" aria-current="page">Lista postów</a></li>
                    @if(request()->session()->get('api_token', null))
                        <li class="nav-item"><a href="{{ route('users') }}" class="nav-link link-dark px-2 active" aria-current="page">Lista użytkowników</a></li>
                    @endif
                </ul>
                <ul class="nav">
                    @if(request()->session()->get('api_token', null))
                        <li class="nav-item"><a href="{{ route('user.logout') }}" class="nav-link link-dark px-2">Wyloguj się</a></li>
                    @else
                        <li class="nav-item"><a href="{{ route('user.login') }}" class="nav-link link-dark px-2">Logowanie</a></li>
                        <li class="nav-item"><a href="{{ route('user.register') }}" class="nav-link link-dark px-2">Rejestracja</a></li>
                    @endif
                </ul>
            </div>
        </nav>
        
        <div class="container mt-5">
            @include('panel.partials.messages')
            @yield('content')
        </div>
    </body>
</html>
