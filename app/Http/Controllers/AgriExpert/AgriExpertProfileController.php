<?php

namespace App\Http\Controllers\AgriExpert;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgriExpertProfileController extends Controller
{
    //
    public function profile(){
        $data['user'] = User::where('id', auth()->user()->id)->with(['department', 'designation'])->first();
        return view('agriexpert.profile')->with($data);
    }
}
