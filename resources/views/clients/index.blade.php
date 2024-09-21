@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Client List</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($clients->isEmpty())
        <p>No clients found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>School Name</th>
                    <th>Phones</th>
                    <th>Owner</th>
                    <th>Slogan</th>
                    <th>Main Service</th>
                    <th>Website Name</th>
                    <th>Colored Logo</th>
                    <th>Black/White Logo</th>
                    <th>Cover</th>
                    <th>Actions</th> <!-- Adicionando nova coluna para ações -->
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->school_name }}</td>
                        <td>
                            @if ($client->phones)
                                @foreach ($client->phones as $phone)
                                    {{ $phone }}<br>
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $client->owner }}</td>
                        <td>{{ $client->slogan }}</td>
                        <td>{{ $client->main_service }}</td>
                        <td>{{ $client->website_name }}</td>
                        <td>
                            @if ($client->colored_logo)
                                <img src="{{ $client->colored_logo }}" alt="Colored Logo" width="100">
                            @else
                                No logo available
                            @endif
                        </td>
                        <td>
                            @if ($client->black_white_logo)
                                <img src="{{ $client->black_white_logo }}" alt="Black/White Logo" width="100">
                            @else
                                No logo available
                            @endif
                        </td>
                        <td>
                            @if ($client->cover)
                                <img src="{{ $client->cover }}" alt="Cover" width="100">
                            @else
                                No logo available
                            @endif
                        </td>
                        <td>

                          <div class="btn-group" role="group">

                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>

                          </div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
