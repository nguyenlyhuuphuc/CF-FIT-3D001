<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->with(["prompt" => "select_account"])->redirect();
    }

    public function callback(){
        $userGoogle = Socialite::driver('google')->user();

        $currentUser = User::updateOrCreate([
            'email' => $userGoogle->email,
        ], [
            'email' => $userGoogle->email,
            'name' => $userGoogle->name,
            'google_user_id' => $userGoogle->id,
            'password' => Hash::make($userGoogle->email.'@'. $userGoogle->id)
        ]);
        
        Auth::login($currentUser);

        return redirect()->route('home');
    }
}
