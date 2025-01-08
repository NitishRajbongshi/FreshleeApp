<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoleMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private function getUserRole($userId)
    {
        $rolesmapping = UserRoleMapping::where('user_id', $userId)->orderby('role_id')->get();
        $roles = [];
        foreach ($rolesmapping as $rolemap) {
            $roles[] = $rolemap->role_id;
        }

        Session::push('roles', $roles);

        foreach ($roles as $role)
            if ($role === 'ADMIN')
                return ('ADMIN');


        foreach ($roles as $role)
            if ($rolemap->role_id === 'CM')
                return ('CM');

        foreach ($roles as $role)
            if ($rolemap->role_id === 'M')
                return ('M');

        foreach ($roles as $role)
            if ($rolemap->role_id === 'AE')
                return ('AE');
    }

    public function login()
    {
        if (Auth::check()) {
            return back();
        }
        return view('admin.auth.login');
    }


    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
            $request->session()->regenerate();
            $main_role = $this->getUserRole(auth()->user()->user_id);
            $userName = auth()->user()->name;
            $request->session()->put('name', $userName);
            $request->session()->put('current_role', $main_role);

            if ($main_role == 'ADMIN')
                return redirect()->route('admin.dashboard');
            else if ($main_role == 'CM')
                return redirect()->route('moderator.dashboard');
            else if ($main_role == 'M')
                return redirect()->route('moderator.dashboard');
            else if ($main_role == 'AE')
                return redirect()->route('agriexpert.dashboard');
            else {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'You are not allowed to login without properly assigned role. Contact your administrator.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.auth.register');
        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|min:6'
            ]);

            $create = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            if (!$create) {
                return redirect()->back()->with('message', 'Regisration failed');
            }
            return view('admin.auth.register')->with('message', 'Registration successful');
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'newPassword' => 'required',
                'confirmPassword' => 'same:newPassword',
            ]);

            $update = User::find(auth()->user()->id)->update(['password' => Hash::make($request->newPassword)]);

            return response()->json(['status' => 1, 'message' => 'Password change successfully.']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 0, 'message' => $th->getMessage()]);
        }
    }

    public function logoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}