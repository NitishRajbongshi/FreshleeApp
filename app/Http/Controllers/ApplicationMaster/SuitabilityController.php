<?php

namespace App\Http\Controllers\ApplicationMaster;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SuitabilityController extends Controller
{


    public function index()
    {
        $data = DB::table('ag_crop_master_suitability as u')
            ->select('u.id', 'u.crop_type_cd', 'u.crop_name_cd', 'u.soil', 'u.soil_as', 'u.sowing_time', 'u.sowing_time_as')
            ->get();

        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->toArray();

        // Sort the crop types alphabetically
        asort($cropTypes);

        // Convert all letters to uppercase
        $cropTypes = array_map(function ($description) {
            return strtoupper($description); // Convert all letters to uppercase
        }, $cropTypes);
        $cropNames = []; // Initialize empty array for crop names

        return view('admin.appmaster.suitability', [
            'data' => $data,
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

        $cropNames = $cropNames->map(function ($description) {
            return strtoupper($description); // Convert each crop name to uppercase
        });



        return response()->json($cropNames);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_master_suitability,id',
            'crop_type_cd' => 'required|exists:ag_crop_type_master,crop_type_cd',
            'crop_name_cd' => 'required|exists:ag_crop_name_master,crop_name_cd',
            'soil' => 'required|string',
            'soil_as' => 'nullable|string',
            'sowing_time' => 'nullable|string',
            'sowing_time_as' => 'nullable|string',
        ]);

        DB::table('ag_crop_master_suitability')
            ->where('id', $validated['id'])
            ->update([
                'crop_type_cd' => $validated['crop_type_cd'],
                'crop_name_cd' => $validated['crop_name_cd'],
                'soil' => $validated['soil'],
                'soil_as' => $validated['soil_as'],
                'sowing_time' => $validated['sowing_time'],
                'sowing_time_as' => $validated['sowing_time_as'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

        $cropSuitability = DB::table('ag_crop_master_suitability')
            ->where('id', $validated['id'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Suitability updated successfully.',
            'cropSuitability' => $cropSuitability
        ]);
        // return redirect()->route('admin.appmaster.suitability')->with('success', 'Suitability updated successfully.');
    }
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'soil' => 'required|string|max:255',
                'soil_as' => 'nullable|string|max:255',
                'sowing_time' => 'nullable|string|max:255',
                'sowing_time_as' => 'nullable|string|max:255',
                'crop_type_cd' => 'required|integer|exists:ag_crop_type_master,crop_type_cd',
                'crop_name_cd' => 'required|integer|exists:ag_crop_name_master,crop_name_cd',
            ], [
                'crop_type_cd.required' => 'The crop type is required.',
                'crop_name_cd.required' => 'The crop name is required.',
            ]);

            $maxId = DB::table('ag_crop_master_suitability')
                ->max('id');

            $nextId = $maxId ? $maxId + 1 : 1; // Increment the highest ID or start at 1

            // Insert the new crop information
            DB::table('ag_crop_master_suitability')->insert([
                'id' => $nextId,
                'soil' => $validated['soil'],
                'soil_as' => $validated['soil_as'],
                'sowing_time' => $validated['sowing_time'],
                'sowing_time_as' => $validated['sowing_time_as'],
                'crop_type_cd' => $validated['crop_type_cd'], // Add the crop type
                'crop_name_cd' => $validated['crop_name_cd'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
                'created_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

            // return redirect()->route('admin.appmaster.suitability')->with('success', 'Suitability created successfully.');

            return redirect()->back()->with('success', 'Suitability created successfully.');
        }

        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->map(function ($description) {
            return strtoupper($description);
        });

        $cropTypes = $cropTypes->sort();

        return view('admin.appmaster.createsuitability', ['cropTypes' => $cropTypes]);
    }


    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_master_suitability,id',
        ]);

        DB::table('ag_crop_master_suitability')
            ->where('id', $validated['id'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Suitability deleted successfully.',
            'id' => $validated['id']
        ]);

        // return redirect()->route('admin.appmaster.suitability')->with('success', 'Suitability deleted successfully.');
    }
}
