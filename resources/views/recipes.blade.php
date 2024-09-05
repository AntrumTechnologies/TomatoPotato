@extends('layouts.app', ['title' => 'My Recipes'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>My Recipes</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <a href="{{ route('create-recipe') }}" class="btn btn-primary">Create new recipe</a>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
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
                            @if ($recipe->favorite == "true")
                            <3
                            @else
                            &nbsp;
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>   
        </div>
    </div>
</div>
@endsection
