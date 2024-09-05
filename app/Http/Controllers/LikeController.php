<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like($recipe_id) {
        $like = Like::where('user_id', Auth::id())->where('recipe_id', $recipe_id)->count();

        if ($like == 0) {
            $like = new Like([
                'user_id' => Auth::id(),
                'recipe_id' => $recipe_id,
            ]);

            $like->save();
        }

        return redirect()->route('show-recipe', ['id' => $recipe_id]);
    }

    public function unlike($recipe_id) {
        $like = Like::where('user_id', Auth::id())->where('recipe_id', $recipe_id)->first();

        if ($like) {
            $like->delete();
        }

        return redirect()->route('show-recipe', ['id' => $recipe_id]);
    }
}
