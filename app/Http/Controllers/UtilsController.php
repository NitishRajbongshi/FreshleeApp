<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UtilsController extends Controller
{
    function getEncryptedPassword($pass){
        $hash = Hash::make($pass);
        return $hash;
    }

    function attemptLogin2($user_id, $pass) {
        // dd($user_id);
        $user = User::where('user_id', $user_id)->get();
        // dd($user);
        if(!$user)
            return false;
        else
            // Auth::attempt(['user_id' => $user_id, 'password' => $pass]);
        {   if( Auth::attempt(['user_id' => $user_id, 'password' => $pass]) )
                return true;
            else 
                return "0";
        }
    }

    function attemptLogin($request) {
        // dd($user_id);
        $payload = json_decode($request->getContent(), true);
        dd($payload);
        $user_id = $payload['userid'];
        $pass = $payload['password'];

        $user = User::where('user_id', $user_id)->get();
        // dd($user);
        if(!$user)
            return false;
        else
            // Auth::attempt(['user_id' => $user_id, 'password' => $pass]);
        {   if( Auth::attempt(['user_id' => $user_id, 'password' => $pass]) )
                return true;
            else 
                return "0";
        }
    }

    
}
