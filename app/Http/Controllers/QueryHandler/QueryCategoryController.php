<?php

namespace App\Http\Controllers\QueryHandler;

use App\Http\Controllers\Controller;
use App\Models\Query\QueryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QueryCategoryController extends Controller
{
    public function index (){
        $data['QueryCategory'] = QueryCategory::get()->latest();
        // return view('Moderator.QueryCategory')->with($data);
    }

    public function createCategory(Request $request) {
        $validator = Validator::make($request->all(),[
            'category_name' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 0,'message' => $validator->errors()->first() ]);
        }
        
        $create = QueryCategory::create(['catg_descr'=> $request->category_name]);

        if(!$create) 
            return response()->json(['status'=>0, 'message'=>'Error creating  new category !']);
        else
            return response()->json(['status'=>1, 'message'=>'New Category created successfully.']);
        
    }
}
