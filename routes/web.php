<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GroceryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes');
    Route::post('/like', [RecipeController::class, 'like']);
    Route::post('/unlike', [RecipeController::class, 'unlike']);

    Route::view('/recipe/create', 'recipe.create')->name('create-recipe');
    Route::post('/recipe/store', [RecipeController::class, 'store'])->name('store-recipe');

    Route::get('/recipe/like/{id}', [LikeController::class, 'like'])->name('like-recipe');
    Route::get('/recipe/unlike/{id}', [LikeController::class, 'unlike'])->name('unlike-recipe');

    Route::get('/recipe/edit/{id}', [RecipeController::class, 'edit'])->name('edit-recipe');
    Route::post('/recipe/update', [RecipeController::class, 'update'])->name('update-recipe');
    Route::post('/recipe/delete', [RecipeController::class, 'delete'])->name('delete-recipe');

    Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('show-recipe');

    // Route::get('/groceries', [GroceryController::class, 'index']);
    // Route::post('/groceries/store', [GroceryController::class, 'store']);
    // Route::post('/groceries/complete', [GroceryController::class, 'complete']);
    // Route::post('/groceries/undo', [GroceryController::class, 'undo']);
    // Route::post('/groceries/delete', [GroceryController::class, 'delete']);

    Route::get('/user', [UserController::class, 'me'])->name('my-profile');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('show-user');
    Route::get('/user/edit', [UserController::class, 'edit'])->name('edit-user');
    Route::post('/user/update', [UserController::class, 'update'])->name('update-user');
});
