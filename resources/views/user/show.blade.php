@extends('layouts.app', ['title' => 'Profile'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>{{ $user->name }}</h2>
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
                            <p class="card-text">Invented by: {{ $recipe->user_name }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>   
        </div>
    </div>
</div>
@endsection
