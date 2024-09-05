<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Recipe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Show all recipes available
        $recipes = Recipe::leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', ['recipes' => $recipes]);
    }

    public function search(Request $request)
    {
        $recipes = Recipe::leftJoin('users', 'users.id', '=', 'recipes.user_id')
            ->select('recipes.*', 'users.id as user_id', 'users.name as user_name')
            ->orWhere('recipes.name', 'LIKE', '%'. $request->get('query') .'%')
            ->orWhere('users.name', 'LIKE', '%'. $request->get('query') .'%')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('search', ['recipes' => $recipes, 'query' => $request->get('query')]);
    }
}
