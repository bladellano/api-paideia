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
                    <th>CNPJ</th>
                    <th>Address</th>
                    <th>Phones</th>
                    <th>Owner</th>
                    <th>Slogan</th>
                    <th>Main Service</th>
                    <th>Website Name</th>
                    <th>Colored Logo</th>
                    <th>Black/White Logo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->school_name }}</td>
                        <td>{{ $client->cnpj }}</td>
                        <td>{{ $client->address }}</td>
                        <td>
                            @foreach ($client->phones as $phone)
                                {{ $phone }}<br>
                            @endforeach
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
