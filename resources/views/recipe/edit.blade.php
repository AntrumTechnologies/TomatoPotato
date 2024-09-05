@extends('layouts.app', ['title' => 'Create new recipe'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>{{ $recipe->name }}</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
    </div>

    <form method="post" action="{{ route('update-recipe') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="id" value="{{ $recipe->id }}">

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <h4>Recipe details</h4>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Recipe name <span class="text-danger">*</span></label>
                    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="@if(old('name')){{ old('name') }}@else{{ $recipe->name }}@endif">

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
                @foreach ($recipe->ingredients as $ingredient)
                <div class="mb-3" id="ingredient{{ $loop->iteration }}">
                    <div class="input-group mb-3">
                        <input class="form-control @error('ingredients[]') is-invalid @enderror" id="ingredients[]" name="ingredients[]" type="text" placeholder="Ingredient..." value="@if(old('ingredients[]')){{ old('ingredients[]') }}@else{{ $ingredient }}@endif">

                        <input class="form-control @error('quantities[]') is-invalid @enderror" id="quantities[]" name="quantities[]" type="text" placeholder="Quantity..." value="@if(old('quantities[]')){{ old('quantities[]') }}@else{{ $recipe->quantities[$loop->iteration-1] }}@endif" style="max-width: 150px">

                        <select class="form-select" id="units[]" name="units[]" style="max-width: 120px">
                            <option value="x"@if ($recipe->units[$loop->iteration-1] == "x") selected @endif>x (number)</option>
                            <option value="mL"@if ($recipe->units[$loop->iteration-1] == "mL") selected @endif>mL</option>
                            <option value="grams"@if ($recipe->units[$loop->iteration-1] == "grams") selected @endif>grams</option>
                            <option value="kilograms"@if ($recipe->units[$loop->iteration-1] == "kilograms") selected @endif>kilograms</option>
                        </select>
                    </div>

                    @error('ingredients[]')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @endforeach
            </div>

            <span id="add-ingredient" class="btn btn-primary btn-sm">Add ingredient</span>
            <span id="remove-ingredient" class="btn btn-danger btn-sm">Remove ingredient</span>
        </div>
    </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <h4>Steps</h4>

                <div id="recipe-steps">
                    @foreach ($recipe->stepdescription as $step)
                    <div class="mb-3" id="step{{ $loop->iteration }}">
                        <h5><label for="stepdescription[]" class="form-label">Step {{ $loop->iteration }}</label></h5>
                        <textarea class="form-control @error('stepname[]') is-invalid @enderror" id="stepdescription[]" name="stepdescription[]" rows="4">@if(old('stepname[]')){{ old('stepname[]') }}@else{{ $step }}@endif</textarea>

                        @error('stepname[]')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    @endforeach
                </div>

                <span id="add-step" class="btn btn-primary btn-sm">Add step</span>
                <span id="remove-step" class="btn btn-danger btn-sm">Remove step</span>

                <hr />

                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="{{ route('show-recipe', $recipe->id) }}" class="btn btn-warning">Cancel</a>
            </div>
        </div>
    </form>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <h4>Delete recipe</h4>
            
            <form method="post" action="{{ route('delete-recipe') }}">
                @csrf

                <input type="hidden" name="id" value="{{ $recipe->id }}">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure to delete this recipe? This action cannot be undone.')">Delete</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Wait until the window finishes loaded before executing any script
    window.onload = function() {
        // Initialize the number of steps
        var steps = @php echo sizeof($recipe->stepdescription) @endphp;
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

        var ingredients = @php echo sizeof($recipe->ingredients) @endphp;
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
