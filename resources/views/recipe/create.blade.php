@extends('layouts.app', ['title' => 'Create new recipe'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Create new recipe</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <form method="post" action="{{ route('store-recipe') }}" enctype="multipart/form-data">
        @csrf

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Recipe details</h4>
            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="@if(old('name')){{ old('name') }}@endif">

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="picture" class="form-label">Picture</label>
                <input class="form-control @error('picture') is-invalid @enderror" type="file" id="picture" name="picture" accept="image/png, image/jpeg, image/jpg" value="@if(old('name')){{ old('name') }}@endif">

                @error('picture')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Ingredients</h4>

            <div id="recipe-ingredients">
                <div class="mb-3" id="ingredient1">
                    <div class="input-group mb-3">
                        <input class="form-control @error('ingredients[]') is-invalid @enderror" id="ingredients[]" name="ingredients[]" type="text" placeholder="Ingredient..." value="@if(old('ingredients[]')){{ old('ingredients[]') }}@endif">

                        <input class="form-control @error('quantities[]') is-invalid @enderror" id="quantities[]" name="quantities[]" type="text" placeholder="Quantity..." value="@if(old('quantities[]')){{ old('quantities[]') }}@endif" style="max-width: 150px">

                        <select class="form-select" id="units[]" name="units[]" style="max-width: 120px">
                            <option value="x" selected>x (number)</option>
                            <option value="mL">mL</option>
                            <option value="grams">grams</option>
                            <option value="kilograms">kilograms</option>
                        </select>
                    </div>

                    @error('ingredients[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @error('quantities[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    @error('units[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <span id="add-ingredient" class="btn btn-primary btn-sm">Add ingredient</span>
            <span id="remove-ingredient" class="btn btn-danger btn-sm">Remove ingredient</span>
        </div>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Steps</h4>

            <div id="recipe-steps">
                <div class="mb-3" id="step1">
                    <h5><label for="stepdescription[]" class="form-label">Step 1</label></h5>
                    <textarea class="form-control @error('stepdescription[]') is-invalid @enderror" id="stepdescription[]" name="stepdescription[]" rows="4">@if(old('stepdescription[]')){{ old('stepdescription[]') }}@endif</textarea>

                    @error('stepdescription[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <span id="add-step" class="btn btn-primary btn-sm">Add step</span>
            <span id="remove-step" class="btn btn-danger btn-sm">Remove step</span>

            <hr />

            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('recipes') }}" class="btn btn-danger">Cancel</a>
        </div>
    </div>

    </form>
</div>
<script>
    // Wait until the window finishes loaded before executing any script
    window.onload = function() {
        // Initialize the number of steps
        var steps = 1;
        // Select the add_activity button
        var addButton = document.getElementById("add-step");
        // Select the element to append to
        var recipeSteps = document.getElementById("recipe-steps");

        // Attach handler to the button click event
        addButton.onclick = function() {
            // Increment the steps
            steps += 1;
            // Add a new step using the correct step number
            var newStep = document.createElement('div');
            recipeSteps.appendChild(newStep);
            newStep.outerHTML = '<div class="mb-3" id="step' + steps + '"><h5><label for="stepdescription[]" class="form-label">Step ' + steps + '</label></h5><textarea class="form-control @error('stepname[]') is-invalid @enderror" id="stepdescription[]" name="stepdescription[]" rows="4">@if(old('stepname[]')){{ old('stepname[]') }}@endif</textarea>@error('stepname[]')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div>';
        }

        var removeButton = document.getElementById("remove-step");
        removeButton.onclick = function() {
            if (steps > 1) {
                var recipeStep = document.getElementById("step" + steps);
                recipeStep.remove();
                steps -= 1;
            }
        }

        var ingredients = 1;
        var addIngredientButton = document.getElementById("add-ingredient");
        var recipeIngredients = document.getElementById("recipe-ingredients");

        addIngredientButton.onclick = function() {
            ingredients += 1;
            var newIngredient = document.createElement('div');
            recipeIngredients.appendChild(newIngredient);
            newIngredient.outerHTML = '<div class="mb-3" id="ingredient' + ingredients + '"><div class="input-group mb-3"><input class="form-control @error('ingredients[]') is-invalid @enderror" id="ingredients[]" name="ingredients[]" type="text" placeholder="Ingredient..." value="@if(old('ingredients[]')){{ old('ingredients[]') }}@endif"><input class="form-control @error('quantities[]') is-invalid @enderror" id="quantities[]" name="quantities[]" type="text" placeholder="Quantity..." value="@if(old('quantities[]')){{ old('quantities[]') }}@endif" style="max-width: 150px"><select class="form-select" id="units[]" name="units[]" style="max-width: 120px"><option value="x" selected>x (number)</option><option value="mL">mL</option><option value="grams">grams</option><option value="kilograms">kilograms</option></select></div>@error('ingredients[]')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div>';
        }

        var removeIngredientButton = document.getElementById("remove-ingredient");
        removeIngredientButton.onclick = function() {
            if (ingredients > 1) {
                var recipeIngredient = document.getElementById("ingredient" + ingredients);
                recipeIngredient.remove();
                ingredients -= 1;
            }
        }
    }
</script>
@endsection
