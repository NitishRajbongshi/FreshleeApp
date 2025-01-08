<?php

namespace App\Http\Controllers\AgriNews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgriNews\AgriNewsCategory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgriNewsCategoryController extends Controller
{
    public function index() {
        // create data to be viewed
        // $data['newsCategories'] = DB::table('tbl_agrinews_category_master')->get();

        $data['newsCategories'] = AgriNewsCategory::get();
        // dd($data);
        return view('admin.agrinews.agrinewscategory')->with($data);
    }

    public function createNewCategory(Request $request) {
        // Insert new category to database

        $validator = Validator::make($request->all(),[
            'createCategoryName' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $category_cd = date('YmdHis', strtotime(Carbon::now()));
        $create = AgriNewsCategory::create([
            'catg_cd' => $category_cd,
            'catg_descr' => $request->createCategoryName
        ]);

        if(!$create){
            return response()->json(['status'=>0, 'message'=>'Error adding category!']);
        }
        return response()->json(['status'=>1, 'message'=>'Category added successfully']);

    }

    public function editNewsCategory(Request $request) {
        // Edit the name of an existing category
        $validator = Validator::make($request->all(), [
            'editCategoryName' => 'required|max:255'
        ]);


        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $category_cd = Crypt::decrypt($request->editCategoryCd);

        $categoryObj = AgriNewsCategory::where('catg_cd', $category_cd);

        // dd($categoryObj);
        if($categoryObj) {
            $categoryObj->update(['catg_descr' => $request->editCategoryName]);
            return response()->json(['status'=>1, 'message'=>'Category updated successfully']);
        }
        else {
            return response()->json(['status'=>0, 'message'=>'Error Updating Category.']);
        }

    }
}
