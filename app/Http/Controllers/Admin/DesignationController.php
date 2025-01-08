<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    //
    public function index(){
        $data['designations'] = Designation::latest()->get();
        return view('admin.master.designation')->with($data);
    }

    public function createDesignation(Request $request){
        $validator = Validator::make($request->all(),[
            'designation_title' => 'required|max:50'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $create = Designation::create([
            'designation_name' => $request->designation_title
        ]);

        if(!$create){
            return response()->json(['status'=>0, 'message'=>'Error creating designation!']);
        }
        return response()->json(['status'=>1, 'message'=>'Designation created successfully']);
    }

    public function editDesignation(Request $request){
        $validator = Validator::make($request->all(),[
            'designation_id' => 'required',
            'edit_designation_title' => 'required|max:50'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $dec_id = Crypt::decrypt($request->designation_id);
        $find_designation = Designation::find($dec_id);

        if(!$find_designation){
            return response()->json(['status'=>0, 'message'=>'Invalid request']);
        }

        $update = Designation::find($dec_id)->update([
            'designation_name' => $request->edit_designation_title
        ]);

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error updating designation!']);
        }
        return response()->json(['status'=>1, 'message'=>'Designation updated successfully']);
    }

    public function delete(Request $request){
        $dec_id = Crypt::decrypt($request->_id);
        $get_data = Designation::find($dec_id);

        if(!$get_data){
            return response()->json(['status'=>0, 'message'=>'Invalid delete request']);
        }
        $delete = $get_data->delete();
        if(!$delete){
            return response()->json(['status'=>0, 'message'=>'Error deleting designation!']);
        }
        return response()->json(['status'=>1, 'message'=>'Designation deleted successfully']);
    }
}
