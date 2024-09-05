<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Recipe;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Image;

class RecipeController extends Controller
{
    /**
     * Shows all your recipes
     */
    public function index() {
        // Show all recipes created by users, and all liked recipes
        $my_recipes = Recipe::where('user_id', Auth::id())
            ->leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name');
        
        $my_recipes = $my_recipes->addSelect(DB::raw("'false' as favorite"))->get();

        $liked_recipes = Recipe::leftJoin('likes', 'likes.recipe_id', '=', 'recipes.id')
            ->leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->where('likes.user_id', Auth::id())
            ->where('recipes.user_id', '!=', Auth::id())
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name', 'likes.created_at as created_at');

        $liked_recipes = $liked_recipes->addSelect(DB::raw("'true' as favorite"))->get();

        $recipes = $my_recipes->merge($liked_recipes);
        $recipes = $recipes->sortBy('created_at')->reverse();

        return view("recipes", ['recipes' => $recipes]);
    }

    public function show($id) {
        $recipe = Recipe::where('recipes.id', $id)
            ->leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name')
            ->first();

        $recipe->stepdescription = json_decode($recipe->stepdescription);
        $recipe->ingredients = json_decode($recipe->ingredients);
        $recipe->quantities = json_decode($recipe->quantities);
        $recipe->units = json_decode($recipe->units);

        $recipe->is_favorite = false;
        $like = Like::where('user_id', Auth::id())->where('recipe_id', $id)->count();
        if ($like > 0) {
            $recipe->is_favorite = true;
        }

        return view('recipe.show', ['recipe' => $recipe]);
    }

    public function edit($id) {
        $recipe = Recipe::where('recipes.id', $id)
            ->leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name')
            ->first();

        // Make sure the recipe belongs to the user
        if ($recipe->user_id != Auth::id()) {
            return redirect()->route('show-recipe', ['id' => $recipe->id]);
        }

        $recipe->stepdescription = json_decode($recipe->stepdescription);
        $recipe->ingredients = json_decode($recipe->ingredients);
        $recipe->quantities = json_decode($recipe->quantities);
        $recipe->units = json_decode($recipe->units);

        return view('recipe.edit', ['recipe' => $recipe]);
    }

    public function update(Request $request) {
        $request->validate([
            'id' => 'required|exists:recipes',
            'name' => 'sometimes|min:4|max:200',
            'picture' => 'sometimes|mimes:jpg,jpeg,png|max:4096',
            'stepdescription' => 'sometimes',
            'ingredients' => 'sometimes',
            'quantities' => 'sometimes',
            'units' => 'sometimes',
        ]);

        $recipe = Recipe::findOrFail($request->get('id'));

        // Make sure the recipe belongs to the user
        if ($recipe->user_id != Auth::id()) {
            return redirect()->route('show-recipe', ['id' => $recipe->id]);
        }

        if ($request->has('name') && $request->get('name') != $recipe->name) {
            $recipe->name = $request->get('name');
        }

        if ($request->has('picture')) {
            $file = $request->file('picture');
            $path = $file->hashName('recipes');
            $picture = Image::make($file)->fit(900, 900)->encode('png', 100);
            $store = Storage::disk('public')->put($path, (string) $picture->encode());

            $recipe->picture = $path;
        }

        if ($request->has('stepdescription')) {
            $recipe->stepdescription = json_encode($request->get('stepdescription'));
        }

        if ($request->has('ingredients')) {
            $recipe->ingredients = json_encode($request->get('ingredients'));
        }

        if ($request->has('quantities')) {
            $recipe->quantities = json_encode($request->get('quantities'));
        }

        if ($request->has('units')) {
            $recipe->units = json_encode($request->get('units'));
        }

        $recipe->save();

        return redirect()->route('show-recipe', ['id' => $recipe->id]);
    }

    /**
     * When you create a new recipe this function stores it
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:4|max:200',
            'picture' => 'sometimes|mimes:jpg,jpeg,png|max:4096',
            'stepdescription' => 'required',
            'ingredients' => 'required',
            'quantities' => 'required',
            'units' => 'required',
        ]);

        $path = 'recipes/placeholder.png';
        if ($request->has('picture')) {
            $file = $request->file('picture');
            $path = $file->hashName('recipes');
            $picture = Image::make($file)->encode('png', 100);
            $store = Storage::disk('public')->put($path, (string) $picture->encode());
        }

        $recipe = new Recipe([
            'name' => $request->get('name'),
            'picture' => $path,
            'user_id' => Auth::id(),
            'stepdescription' => json_encode($request->get('stepdescription')),
            'ingredients' => json_encode($request->get('ingredients')),
            'quantities' => json_encode($request->get('quantities')),
            'units' => json_encode($request->get('units')),
        ]);

        $recipe->save();

        return redirect()->route('show-recipe', ['id' => $recipe->id]);
    }

    public function delete(Request $request) {
        $request->validate([
            'id' => 'required|exists:recipes',
        ]);

        $recipe = Recipe::findOrFail($request->get('id'));

        // Make sure the recipe belongs to the user
        if ($recipe->user_id != Auth::id()) {
            return redirect()->route('show-recipe', ['id' => $recipe->id]);
        }

        if ($recipe->picture != null && $recipe->picture != "recipes/placeholder.png") {
            Storage::disk('public')->delete($recipe->picture);
            $recipe->picture = "recipe/placeholder.png";
            $recipe->save();
        }

        $recipe->delete();

        return redirect()->route('recipes');
    }
}
