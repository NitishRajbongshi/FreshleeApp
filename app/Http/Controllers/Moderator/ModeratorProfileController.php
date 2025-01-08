<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ModeratorProfileController extends Controller
{
    //
    public function profile(){
        $data['user'] = User::where('id', auth()->user()->id)->with(['department', 'designation'])->first();
        return view('moderator.profile')->with($data);
    }
}
