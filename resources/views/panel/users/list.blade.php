@extends('panel.layout')

@section('content')
    <h2>Lista użytkowników</h2>
        
    <div class="text-end">
        <a href="{{ route('user.create') }}" class="btn btn-primary">Nowy użytkownik</a>
    </div>
        
    <table class="table">
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Rola</th>
                <th>E-mail</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($data['data']))
                @foreach($data['data'] as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['user_role'] }}</td>
                        <td>{{ $row['email'] }}</td>
                        <td class="text-end">
                            <a href="{{ route('user.edit', $row['id']) }}" class="btn btn-primary">Edytuj</a>
                            
                            <a href="#" data-bs-toggle="modal" data-bs-target="#removeModal{{ $row['id'] }}" class="btn btn-danger">Usuń</a>
                            <div class="modal fade" id="removeModal{{ $row['id'] }}" tabindex="-1" aria-labelledby="removeModal{{ $row['id'] }}Label" aria-hidden="true">
                                <div class="modal-dialog text-start">
                                    <form method="post" action="{{ route('user.delete', $row['id']) }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="removeModal{{ $row['id'] }}Label">Usuń użytkownika</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Usunąć wybranego użytkownika?
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
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">Brak danych</td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="mb-3 row">
        @if($data['total_pages'] > 1)
            <div class="col text-start">
                @if($data['current_page'] > 1)
                    <a href="{{ route('users', ['page' => $data['current_page'] - 1]) }}">&laquo; Poprzednia strona</a>
                @else
                    &laquo; Poprzednia strona
                @endif
            </div>
            
            <div class="col text-end">
                @if($data['has_more'])
                    <a href="{{ route('users', ['page' => $data['current_page'] + 1]) }}">Następna strona &raquo;</a>
                @else
                    Następna strona &raquo;
                @endif
            </div>
        @endif
    </div>
@endsection