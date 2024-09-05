<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\MagicEmail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use MagicLink\Actions\LoginAction;
use MagicLink\MagicLink;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|exists:users',
        ]);

        $user = User::where('email', $request->get('email'))->first();

        $loginAction = new LoginAction($user);
        $loginAction->remember();

        $urlToAutoLogin =  MagicLink::create($loginAction, null)->url;
        
        $array = [
            'name' => $user->name,
            'email' => $user->email,
            'url' => $urlToAutoLogin,
        ];

        $user->notify(new MagicEmail($array));

        return view("auth.verify", ['email' => $user->email]);
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'magiclink_token' => 'sometimes',
        ]);

        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        // Now save the user
        $user->save();

        $magicUrl =  MagicLink::create(new LoginAction($user))->url;
        $array = [
            'name' => $user->name,
            'email' => $user->email,
            'url' => $magicUrl,
        ];

        $user->notify(new MagicEmail($array));
        return view("auth.verify", ['email' => $user->email]);
    }
}