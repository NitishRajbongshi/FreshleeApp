<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    //
    public function index(){
        $data['departments'] = Department::latest()->get();
        return view('admin.master.department')->with($data);
    }
    
    public function createDepartment(Request $request){
        $validator = Validator::make($request->all(),[
            'department_title' => 'required|max:150'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $create = Department::create([
            'department_name' => $request->department_title
        ]);

        if(!$create){
            return response()->json(['status'=>0, 'message'=>'Error adding department!']);
        }
        return response()->json(['status'=>1, 'message'=>'Department added successfully']);
    }

    
    public function editDepartment(Request $request){
        $validator = Validator::make($request->all(),[
            'department_id' => 'required',
            'edit_department_title' => 'required|max:250'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $dec_id = Crypt::decrypt($request->department_id);
        $find_department = Department::find($dec_id);

        if(!$find_department){
            return response()->json(['status'=>0, 'message'=>'Invalid request']);
        }

        $update = Department::find($dec_id)->update([
            'department_name' => $request->edit_department_title
        ]);

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error updating department!']);
        }
        return response()->json(['status'=>1, 'message'=>'Department updated successfully']);
    }

    public function delete(Request $request){
        $dec_id = Crypt::decrypt($request->_id);
        $get_data = Department::find($dec_id);

        if(!$get_data){
            return response()->json(['status'=>0, 'message'=>'Invalid delete request']);
        }
        $delete = $get_data->delete();
        if(!$delete){
            return response()->json(['status'=>0, 'message'=>'Error deleting department!']);
        }
        return response()->json(['status'=>1, 'message'=>'Department deleted successfully']);
    }

}
