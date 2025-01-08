<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FixedMasters\District;
use App\Models\FixedMasters\State;
use Illuminate\Http\Request;
use App\Models\Office;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    public function index() {
        $data['offices'] = Office::all();
        $data['states']  = State::all();
        $data['districts'] = District::all();
        
        return view('admin.master.office')->with($data);
    }

    public function createOffice(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(),[
            'state' => 'required',
            'district' => 'required',
            'name' => 'required',
            'address' => 'required',
            'contact' => 'required|digits:10|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $create = Office::create([
            'name' => $request->name,
            'address' => $request->address,
            'district' => $request->district,
            'state' => $request->state,
            'contact' => $request->contact
        ]);

        if(!$create){
            return response()->json(['status'=>0, 'message'=>'Error creating new office!']);
        }
        return response()->json(['status'=>1, 'message'=>'New Office created successfully']);
    }

    public function editOffice(Request $request) {

        $validator = Validator::make($request->all(),[
            'editState' => 'required',
            'editDistrict' => 'required',
            'editName' => 'required',
            'editAddress' => 'required',
            'editContact' => 'required|digits:10|numeric'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }
        
        $id = Crypt::decrypt($request->editId);
        $find_office = Office::find($id);

        if(!$find_office){
            return response()->json(['status'=>0, 'message'=>'Error Invalid office selected!']);
        }

        $update = $find_office->update([
            'name' => $request->editName,
            'address' => $request->editAddress,
            'district' => $request->editDistrict,
            'state' => $request->editState,
            'contact' => $request->editContact
        ]);

        if(!$update){
            return response()->json(['status'=>0, 'message'=>'Error updating office details!']);
        }
        return response()->json(['status'=>1, 'message'=>'Office details updated successfully']);
        
    }
}
