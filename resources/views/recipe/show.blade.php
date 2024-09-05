@extends('layouts.app', ['title' => 'Recipe'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex">
                <div class="me-auto">
                    <h2>{{ $recipe->name }}</h2>
                </div>
                
                @if ($recipe->user_id == Auth::id())
                <div class="ms-auto">
                    <a class="btn btn-primary btn-sm" href="{{ route('edit-recipe', $recipe->id) }}">Edit recipe</a>
                </div>
                @endif
            </div>

            <p>Invented by: <a href="{{ route('show-user', $recipe->user_id) }}">{{ $recipe->user_name }}</a></p>

            @if ($recipe->is_favorite)
            <a class="btn btn-secondary btn-sm" href="{{ route('unlike-recipe', $recipe->id) }}">Remove from favorites</a>
            @else
            <a class="btn btn-secondary btn-sm" href="{{ route('like-recipe', $recipe->id) }}">Add to favorites</a>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="col-md-8 mt-4">
            @if (!empty($recipe->picture) && $recipe->picture != "recipes/placeholder.png")
            <img src="/storage/{{ $recipe->picture }}" alt="Picture of {{ $recipe->name }} recipe" style="width: 100%; max-width: 400px; border-radius: 5px;" />
            @endif
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Ingredients</h4>
            
            @if ($recipe->ingredients != null && $recipe->ingredients[0] != null && sizeof($recipe->ingredients) > 0)
                <ul>
                @foreach ($recipe->ingredients as $ingredient)
                    <li>{{ $ingredient }} @if($recipe->quantities[$loop->iteration-1] != "")({{ $recipe->quantities[$loop->iteration-1] }} {{ $recipe->units[$loop->iteration-1] }})@endif</li>
                @endforeach
                </ul>
            @else 
                <p>No ingredients have been added to this recipe.</p>
            @endif
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Recipe details</h4>
            
            @if ($recipe->stepdescription != null && $recipe->stepdescription[0] != null && sizeof($recipe->stepdescription) > 0)
                @foreach ($recipe->stepdescription as $step)
                    <h5>Step {{ $loop->iteration }}</h5>
                    <p>{{ $step }}</p>
                @endforeach
            @else
                <p>No steps have been added to this recipe.</p>
            @endif
        </div>
    </div>
</div>
@endsection
