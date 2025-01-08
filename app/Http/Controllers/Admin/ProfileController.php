<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function profile(){
        $data['user'] = User::where('id', auth()->user()->id)->with(['department', 'designation'])->first();
        return view('admin.profile')->with($data);
    }
}
