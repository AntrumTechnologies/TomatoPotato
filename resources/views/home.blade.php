@extends('layouts.app', ['title' => 'Home'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Hi {{ Auth::user()->name }}!</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <form method="get" action="{{ route('search') }}">
                <div class="input-group mb-3">
                    <input class="form-control @error('query') is-invalid @enderror" id="query" name="query" type="text" placeholder="What's cooking?" value="@if(old('query')){{ old('query') }}@endif" required>
                    <button type="submit" class="btn btn-primary">Search</button>

                    @error('query')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>All recipes</h4>
            <div class="row">
                @foreach ($recipes as $recipe)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <a href="{{ route('show-recipe', $recipe->id) }}">
                            <img src="/storage/{{ $recipe->picture }}" class="card-img-top" alt="Picture of {{ $recipe->name }} recipe" />
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('show-recipe', $recipe->id) }}">{{ $recipe->name }}</a></h5>
                            <p class="card-text">Invented by: <a href="{{ route('show-user', $recipe->user_id) }}">{{ $recipe->user_name }}</a></p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div> 
        </div>
    </div>
</div>
@endsection
