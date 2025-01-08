<?php

namespace App\Http\Controllers\ApplicationMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CropTypeController extends Controller
{
    public function index()
    {
        $data = DB::table('ag_crop_type_master as u')
            ->select('u.crop_type_cd', 'u.crop_type_desc', 'u.crop_type_desc_as')
            ->get();

        return view('admin.appmaster.croptype', ["data" => $data]);

    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'crop_type_cd' => 'required|integer',
            'crop_type_desc' => 'required|string',
            'crop_type_desc_as' => 'nullable|string',

        ]);


        DB::table('ag_crop_type_master')
            ->where('crop_type_cd', $validated['crop_type_cd'])
            ->update([
                'crop_type_desc' => $validated['crop_type_desc'],
                'crop_type_desc_as' => $validated['crop_type_desc_as'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

        $updatedCropType = DB::table('ag_crop_type_master')
            ->where('crop_type_cd', $validated['crop_type_cd'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Crop Type updated successfully.',
            'updatedCropType' => $updatedCropType
        ]);

        // return redirect()->route('admin.appmaster.croptype')->with('success', 'Crop type updated successfully.');
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate the request
            $validated = $request->validate([
                'crop_type' => 'required|string|max:255',
                'crop_type_assamese' => 'nullable|string|max:255',
            ]);

            $maxId = DB::table('ag_crop_type_master')
                ->max('crop_type_cd');

            $nextId = $maxId ? $maxId + 1 : 1; // Increment the highest ID or start at 1

            // Insert the new crop information
            DB::table('ag_crop_type_master')->insert([
                'crop_type_cd' => $nextId,
                'crop_type_desc' => $validated['crop_type'],
                'crop_type_desc_as' => $validated['crop_type_assamese'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
                'created_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

            return redirect()->back()->with('success', 'Crop type added successfully.');

            // return redirect()->route('admin.appmaster.croptype')->with('success', 'Crop type created successfully.');
        }

        return view('admin.appmaster.createcroptype');
    }
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'crop_type_cd' => 'required|integer|exists:ag_crop_type_master,crop_type_cd',
        ]);

        DB::table('ag_crop_type_master')
            ->where('crop_type_cd', $validated['crop_type_cd'])
            ->delete();

        // return redirect()->route('admin.appmaster.croptype')->with('success', 'Crop type deleted successfully.');

        return response()->json([
            'success' => true,
            'message' => 'Crop type deleted successfully.',
            'crop_type_cd' => $validated['crop_type_cd']
        ]);
    }
}
