<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function users(){
        $data['users'] = User::with('designation')->with('department')->with('office')
            ->whereNot('department_id',2)->whereNot('designation_id',2)->get();
        return view('admin.users.users')->with($data);
    }

    public function create(Request $request){
        if($request->isMethod('get')){
            $data['designations'] = Designation::all();
            $data['departments'] = Department::all();
            $data['offices'] = Office::all();
            return view('admin.users.create')->with($data);
        }
        else{

            $validated = $request->validate([
                'name' => 'required|max:255',
                'office_id' =>'required',
                'department_id' => 'required',
                'designation_id' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required|digits:10|numeric',
                'password'=>'required'
            ]);

            // dd($request->input());
            //User::create($validated);
            User::create([
                'name' => $request->name,
                'department_id'=> $request->department_id,
                'designation_id'=>$request->designation_id,
                'email'=>$request->email,
                'user_id'=>$request->email,
                'phone' => $request->phone,
                'password' => $request->password,
                'status' => 1,
                'office_id' => $request->office_id
            ]);
            
            return redirect()->route('admin.users')
                ->with('success', 'User created successfully.');

        }
    }

    public function edit(Request $request, $id){
        $dec_id = Crypt::decrypt($id);
        
        if($request->method('get')) {
            $data['designations'] = Designation::all();
            $data['departments'] = Department::all();
            $data['offices'] = Office::all();

            $data['user'] = User::find($dec_id);
            return view('admin.users.edit')->with($data);
        }
    }

    public function edit_save(Request $request, $id)
    {
        $dec_id = Crypt::decrypt($id);

        $request->validate([
            'name' => 'required|max:255',
            'department_id' => 'required',
            'designation_id' => 'required',
            'office_id' => 'required',
            'phone' => 'required|digits:10|numeric',
        ]);

        $user = User::find($dec_id);
        $user->name  = $request->name;
        $user->designation_id = $request->designation_id;
        $user->department_id = $request->department_id;
        $user->office_id = $request->office_id;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->save();

        return redirect()->route('admin.users')
        ->with('success', 'User update successfull.');
    }

    public function change_staus(Request $request) {
        $decrypted_id = Crypt::decrypt($request->id);
        
        if($decrypted_id == auth()->user()->id)
        {
            return response()->json(['status'=>2, 'message'=>'You can not deactivate yourself.']);
        }
        $user = User::find($decrypted_id);
        $user->status = $request->active;
        $result = $user->save();
        if($result) {
            return response()->json(['status'=>1, 'message'=>'Success']);
        } else {
            return response()->json(['status'=>0, 'message'=>'Something went wrong']);
        }
    }
}
