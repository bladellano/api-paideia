@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Client</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="school_name">School Name:</label>
                <input type="text" class="form-control" id="school_name" name="school_name"
                    value="{{ old('school_name') }}">
            </div>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" class="form-control" id="email" name="email"
                    value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ old('cnpj') }}">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
            </div>

            <div class="form-group">
                <label for="phones">Phones (comma-separated):</label>
                <input type="text" class="form-control" id="phones" name="phones" value="{{ old('phones') }}">
            </div>

            <div class="form-group">
                <label for="owner">Owner:</label>
                <input type="text" class="form-control" id="owner" name="owner" value="{{ old('owner') }}">
            </div>

            <div class="form-group">
                <label for="slogan">Slogan:</label>
                <input type="text" class="form-control" id="slogan" name="slogan" value="{{ old('slogan') }}">
            </div>

            <div class="form-group">
                <label for="main_service">Main Service:</label>
                <input type="text" class="form-control" id="main_service" name="main_service"
                    value="{{ old('main_service') }}">
            </div>

            <div class="form-group">
                <label for="website_name">Website Name:</label>
                <input type="text" class="form-control" id="website_name" name="website_name"
                    value="{{ old('website_name') }}">
            </div>

            <div class="form-group">
                <label for="colored_logo">Colored Logo:</label>
                <input type="file" class="form-control-file" id="colored_logo" name="colored_logo">
            </div>

            <div class="form-group">
                <label for="black_white_logo">Black/White Logo:</label>
                <input type="file" class="form-control-file" id="black_white_logo" name="black_white_logo">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
