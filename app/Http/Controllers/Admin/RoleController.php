<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRoleMapping;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator as Validator;

class RoleController extends Controller
{
    //
    public function index(){
        $data['roles'] = Role::latest()->get();
        return view('admin.master.role')->with($data);
    }

    public function rolemanager(){
        $data['user'] = User::whereNot('department_id',2)->whereNot('designation_id',2)->get();
        $data['role'] = Role::get();
        return view('admin.rolemanager')->with($data);
    }

    public function setRole(Request $request){
        $request->validate([
            'user' => 'required',
            'role' => 'required'
        ]);

        // Set role
        // $setRole = User::find($request->user)->update([
        //     'role_id' => $request->role
        // ]);

        $setRole = UserRoleMapping::insertOrIgnore(['user_id'=> $request->user, 'role_id'=>$request->role]);

        if (!$setRole) {
            return redirect()->back()->with(['status' => 0, 'message' => 'Something went wrong']);
        }

        return redirect()->back()->with(['status' => 1 ,'message' => 'Role set successfully']);

    }
    
    public function createRole(Request $request) {

        $validator = Validator::make($request->all(),[
            'role_title' => 'required|max:100'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $create = Role::create([
            'role_title' => $request->role_title,
            'role_desc'  => $request->role_desc
        ]);

        if(!$create){
            return response()->json(['status'=>0, 'message'=>'Error adding new role!']);
        }
        return response()->json(['status'=>1, 'message'=>'New role added successfully']);
    }

    public function editRole(Request $request) {
        $validator = Validator::make($request->all(),[
            'role_id' => 'required',
            'edit_role_title' => 'required|max:150',
            'edit_role_desc' => 'max:150'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $role_id = Crypt::decrypt($request->role_id);
        $find_role = Role::find($role_id);

        if(!$find_role){
            return response()->json(['status'=>0, 'message'=>'Invalid request']);
        }

        $update = Role::find($role_id)->update([
            'role_title' => $request->edit_role_title,
            'role_desc'  => $request->edit_role_desc
        ]);

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error updating role!']);
        }
        return response()->json(['status'=>1, 'message'=>'Role updated successfully']);
    }
    
    public function deleteRole(Request $request) {

    }
}
