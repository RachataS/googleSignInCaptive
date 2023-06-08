<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;


class GoogleController extends Controller
{
    public function googlepage(){
        $googlelogin = socialite::driver('google')->redirect();
        $redirectUrl = $googlelogin->getTargetUrl();
        info($redirectUrl);
        return response()->json($redirectUrl);
    }

    public function googlecallback(){
        try{
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id',$user->id)->first();

            if($finduser){
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            }
            else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email'=> $user->email,
                    'google_id' => $user->id,
                    'password'=> encrypt("12345"),
                ]);
                Auth::login($newUser);

                return redirect()->intended('dashboard');
            }
        }catch(Exception $e){
            dd($e->getMessage());
}
    }
}
