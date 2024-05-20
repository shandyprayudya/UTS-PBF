<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        $social = Socialite::driver('google')->user();

        $registeredUser = User::where('google_id', $social->id)->first();

        if($registeredUser){


            $user = User::updateOrCreate([
                'google_id' => $social->id,
            ], [
                'name' => $social->name,
                'email'=> $social->email,
                'password' => $social->password,
                'google_token' => $social->token,
                'google_refresh_token' => $social->expiresIn,     
            ]);
        }

        Auth::login($registeredUser);

        return redirect('/dashboard');
    }
}
