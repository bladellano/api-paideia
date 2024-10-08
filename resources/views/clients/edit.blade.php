@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Client</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="school_name">School Name:</label>
                <input type="text" class="form-control" id="school_name" name="school_name"
                    value="{{ old('school_name', $client->school_name) }}">
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" class="form-control" id="email" name="email"
                    value="{{ old('email', $client->email) }}">
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj"
                    value="{{ old('cnpj', $client->cnpj) }}">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address"
                    value="{{ old('address', $client->address) }}">
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city"
                    value="{{ old('city', $client->city) }}">
            </div>

            <div class="form-group">
                <label for="average_grade">Average Grade:</label>
                <input type="text" class="form-control" id="average_grade" name="average_grade"
                    value="{{ old('average_grade', $client->average_grade) }}">
            </div>

            <div class="form-group">
                <label for="phones">Phones (comma-separated):</label>
                <input type="text" class="form-control" id="phones" name="phones"
                    value="{{ old('phones', isset($client->phones) && !empty($client->phones) ? implode(',', $client->phones) : '') }}">
            </div>

            <div class="form-group">
                <label for="owner">Owner:</label>
                <input type="text" class="form-control" id="owner" name="owner"
                    value="{{ old('owner', $client->owner) }}">
            </div>

            <div class="form-group">
                <label for="slogan">Slogan:</label>
                <input type="text" class="form-control" id="slogan" name="slogan"
                    value="{{ old('slogan', $client->slogan) }}">
            </div>

            <div class="form-group">
                <label for="main_service">Main Service:</label>
                <input type="text" class="form-control" id="main_service" name="main_service"
                    value="{{ old('main_service', $client->main_service) }}">
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="website_name">Website Name:</label>
                    <input type="text" class="form-control" id="website_name" name="website_name"
                        value="{{ old('website_name', $client->website_name) }}">
                </div>
    
                <div class="form-group col-md-4">
                    <label for="main_color">Main Color:</label>
                    <input type="color" class="form-control" id="main_color" name="main_color"
                        value="{{ old('main_color', $client->main_color) }}">
                </div>

                <div class="form-group col-md-4">
                    <label for="secondary_color">Secondary Color:</label>
                    <input type="color" class="form-control" id="secondary_color" name="secondary_color"
                        value="{{ old('secondary_color', $client->secondary_color) }}">
                </div>
    
            </div>

            <hr>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="cover">Cover:</label>
                    <input type="file" class="form-control-file" id="cover" name="cover">
                    @if ($client->cover)
                        <img src="{{ $client->cover }}" alt="Colored Logo" width="100">
                    @endif
                </div>
    
                <div class="form-group col-md-4">
                    <label for="colored_logo">Colored Logo:</label>
                    <input type="file" class="form-control-file" id="colored_logo" name="colored_logo">
                    @if ($client->colored_logo)
                        <img src="{{ $client->colored_logo }}" alt="Colored Logo" width="100">
                    @endif
                </div>
    
                <div class="form-group col-md-4">
                    <label for="black_white_logo">Black/White Logo:</label>
                    <input type="file" class="form-control-file" id="black_white_logo" name="black_white_logo">
                    @if ($client->black_white_logo)
                        <img src="{{ $client->black_white_logo }}" alt="Black/White Logo" width="100">
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4">Update</button>
        </form>
    </div>
@endsection
