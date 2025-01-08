<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CropDetailsVarietyController extends Controller
{
    public function cropvarietydetails()
    {
        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->toArray();

        // Sort the crop types alphabetically
        asort($cropTypes);

        // Convert all letters to uppercase
        $cropTypes = array_map(function ($description) {
            return strtoupper($description); // Convert all letters to uppercase
        }, $cropTypes);
        $cropNames = []; // Initialize empty array for crop names

        return view('admin.cropvarietydetails.cropvarietydetails', [
            'cropTypes' => $cropTypes,
            'cropNames' => $cropNames // Pass empty crop names initially
        ]);
    }

    public function getCropNames(Request $request)
    {
        $cropTypeCd = $request->input('crop_type_cd');
        $cropNames = DB::table('ag_crop_name_master')
            ->where('crop_type_cd', $cropTypeCd)
            ->pluck('crop_name_desc', 'crop_name_cd');

        return response()->json($cropNames);
    }


    public function getCropVarieties(Request $request)
    {
        $cropNameCd = $request->input('crop_name_cd');
        $cropVarieties = DB::table('ag_crop_variety_master')
            ->where('crop_name_cd', $cropNameCd)
            ->select('crop_variety_cd', 'crop_variety_desc', 'crop_variety_desc_as', 'crop_variety_dtls', 'crop_variety_dtls_as')
            ->get()
            ->map(function ($item) {
                return [
                    'crop_variety_cd' => $item->crop_variety_cd,
                    'crop_variety_desc' => $item->crop_variety_desc ?? '',
                    'crop_variety_desc_as' => $item->crop_variety_desc_as ?? '',
                    'crop_variety_dtls' => $item->crop_variety_dtls ?? '',
                    'crop_variety_dtls_as' => $item->crop_variety_dtls_as ?? '',
                ];
            });

        return response()->json($cropVarieties);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'crop_variety_cd' => 'required|integer|exists:ag_crop_variety_master,crop_variety_cd',
            'crop_name_id' => 'required|exists:ag_crop_name_master,crop_name_cd',
            'crop_variety_desc' => 'required|string',
            'crop_variety_desc_as' => 'nullable|string',
            'crop_variety_dtls' => 'required|string',
            'crop_variety_dtls_as' => 'nullable|string',
        ]);

        // Replace null values with empty strings
        $validated['crop_variety_desc_as'] = $validated['crop_variety_desc_as'] ?? '';
        $validated['crop_variety_dtls_as'] = $validated['crop_variety_dtls_as'] ?? '';

        // Update the record in the database
        DB::table('ag_crop_variety_master')
            ->where('crop_variety_cd', $validated['crop_variety_cd'])
            ->update([
                'crop_name_cd' => $validated['crop_name_id'],
                'crop_variety_desc' => $validated['crop_variety_desc'],
                'crop_variety_desc_as' => $validated['crop_variety_desc_as'],
                'crop_variety_dtls' => $validated['crop_variety_dtls'],
                'crop_variety_dtls_as' => $validated['crop_variety_dtls_as'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
            ]);


        $updatedVariety = DB::table('ag_crop_variety_master')
            ->where('crop_variety_cd', $validated['crop_variety_cd'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Variety updated successfully.',
            'updatedVariety' => $updatedVariety
        ]);
    }



    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'crop_variety_cd' => 'required|integer|exists:ag_crop_variety_master,crop_variety_cd',
        ]);

        DB::table('ag_crop_variety_master')
            ->where('crop_variety_cd', $validated['crop_variety_cd'])
            ->delete();


        return response()->json([
            'success' => true,
            'message' => 'Variety deleted successfully.',
            'crop_variety_cd' => $validated['crop_variety_cd']
        ]);
    }


    public function create(Request $request)
    {

        if ($request->isMethod('post')) {

            $validated = $request->validate([
                'crop_variety_desc' => 'required|string|max:255',
                'crop_variety_desc_as' => 'nullable|string|max:255',
                'crop_variety_dtls' => 'required|string|max:255',
                'crop_variety_dtls_as' => 'nullable|string|max:255',
                'crop_type_cd' => 'required|integer|exists:ag_crop_name_master,crop_type_cd',
                'crop_name_cd' => 'required|integer|exists:ag_crop_name_master,crop_name_cd',
            ], [
                'crop_type_cd.required' => 'The crop type is required.',
                'crop_name_cd.required' => 'The crop name is required.',
                'crop_variety_desc' => ['Crop variety description is required'],
                'crop_variety_dtls' => ['Crop variety details are required'],
            ]);


            $maxcropvarid = DB::table('ag_crop_variety_master')
                ->max('crop_variety_cd');
            $nextmaxcropvarid = $maxcropvarid ? $maxcropvarid + 1 : 1;


            DB::table('ag_crop_variety_master')->insert([
                'crop_variety_cd' => $nextmaxcropvarid,
                'crop_variety_desc' => $validated['crop_variety_desc'],
                'crop_variety_desc_as' => $validated['crop_variety_desc_as'],
                'crop_variety_dtls' => $validated['crop_variety_dtls'],
                'crop_variety_dtls_as' => $validated['crop_variety_dtls_as'],
                'crop_name_cd' => $validated['crop_name_cd'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
                'created_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

            return redirect()->back()->with('success', 'Variety added successfully.');

            // return redirect()->route('admin.cropvarietydetails')->with('success', 'Variety added successfully.');
        }

        // Fetch crop types for the dropdown
        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->map(function ($description) {
            return strtoupper($description); // Convert each crop type description to uppercase
        });

        // Sort the crop types alphabetically
        $cropTypes = $cropTypes->sort();

        // Handle displaying the form
        return view('admin.cropvarietydetails.createvariety', ['cropTypes' => $cropTypes]);

    }

}
