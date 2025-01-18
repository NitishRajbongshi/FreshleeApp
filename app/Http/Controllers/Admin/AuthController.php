<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoleMapping;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Services\LoginService;

class AuthController extends Controller
{
    protected $apiService;
    public function __construct(LoginService $apiService)
    {
        $this->apiService = $apiService;
    }

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


    public function generateOtp(Request $request)
    {
        $validate = $request->validate([
            'phone' => 'required',
        ]);

        try {
            $phone = $request->phone;
            $otpResponse = $this->apiService->generateOtp($phone);
            if (isset($otpResponse['status']) && $otpResponse['status']) {
                $otp = $otpResponse['otp'];
                Session::put('otp', $otp);
                Session::put('phone', $phone);
                return view('admin.auth.verifyOtp');
            } else {
                throw new \Exception('Failed to send OTP. The API returned an error.');
            }
        } catch (\Exception $exception) {
            Log::error('OTP Generation Error:', ['error' => $exception->getMessage()]);
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again later!'); // error message shown in view
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $otp = $request->first . $request->second . $request->third . $request->fourth;
            $phoneNo = Session::get('phone');
            $userValidation = $this->apiService->validateOtp($otp, $phoneNo);
            // dd($userValidation);
            $verificationStatus = $userValidation['status'];
            if ($verificationStatus === "False") {
                throw new \Exception($userValidation['msg']);
            } else {
                Log::info("varified");
            }
        } catch (\Exception $exception) {
            Log::error('OTP Validation Error:', ['error' => $exception->getMessage()]);
            return redirect()->back()->with('error', 'Failed to Validate OTP!'); // error message shown in view
        }
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
