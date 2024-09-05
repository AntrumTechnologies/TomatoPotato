<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me() {
        return redirect()->route('show-user', ['id' => Auth::id()]);
    }

    public function show($id) {
        $user = User::findOrFail($id);

        $recipes = Recipe::where('user_id', $id)
            ->leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("user.show", ["user" => $user, 'recipes' => $recipes]);
    }
}
