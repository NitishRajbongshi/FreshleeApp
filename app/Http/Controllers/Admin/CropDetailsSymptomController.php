<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CropDetailsSymptomController extends Controller
{
    public function cropsymptomdetails()
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

        return view('admin.cropsymptomdetails.cropsymptomdetails', [
            'cropTypes' => $cropTypes,
            'cropNames' => $cropNames // Pass empty crop names initially
        ]);
    }

    public function getCrop(Request $request)
    {
        $cropTypeCd = $request->input('crop_type_cd');
        $cropNames = DB::table('ag_crop_name_master')
            ->where('crop_type_cd', $cropTypeCd)
            ->pluck('crop_name_desc', 'crop_name_cd');

        return response()->json($cropNames);
    }

    public function getDisease(Request $request)
    {
        $cropNameCd = $request->input('crop_name_cd');

        // Get the disease IDs associated with the selected crop
        $diseaseIds = DB::table('ag_crop_disease_symptom_and_crop_names_mapping')
            ->where('crop_name_cd', $cropNameCd)
            ->pluck('disease_cd');

        // Fetch disease names based on disease IDs
        $diseases = DB::table('ag_crop_disease_master')
            ->whereIn('disease_cd', $diseaseIds)
            ->pluck('disease_name', 'disease_cd');

        return response()->json($diseases);
    }
    public function getCropDiseaseSymptom(Request $request)
    {
        $diseaseNameCd = $request->input('disease_cd');
        $cropNameCd = $request->input('crop_name_cd');

        // Fetch symptom IDs for the given disease and crop name
        $symptomIds = DB::table('ag_crop_disease_symptom_and_crop_names_mapping')
            ->where('crop_name_cd', $cropNameCd)
            ->where('disease_cd', $diseaseNameCd)
            ->pluck('symptom_id');

        // Fetch symptoms based on disease and crop name codes
        $cropDisease = DB::table('ag_crop_disease_symptom_master')
            ->where('crop_disease_cd', $diseaseNameCd)
            ->whereIn('symptom_id', $symptomIds) // Use whereIn for multiple symptom IDs
            ->select('id', 'crop_disease_cd', 'symptom', 'language_cd')
            ->get()
            ->groupBy('language_cd'); // Group symptoms by language

      
        $imagePathsData = DB::table('ag_crop_master_disease_n_crop_name_n_control_measure_mapping')
            ->where('crop_name_cd', $cropNameCd)
            ->where('disease_cd', $diseaseNameCd)
            ->select('imagepath1', 'imagepath2', 'imagepath3')
            ->get()
            ->map(function ($item) {
                $baseUrl = env('APP_URL') . '/external_images';
                $rootPath = config('filesystems.disks.external.root');


                $imagepath1 = $item->imagepath1 ? str_replace($rootPath, '', $item->imagepath1) : '';
                $imagepath2 = $item->imagepath2 ? str_replace($rootPath, '', $item->imagepath2) : '';
                $imagepath3 = $item->imagepath3 ? str_replace($rootPath, '', $item->imagepath3) : '';

                return [
                    'imagepath1' => $imagepath1 ? $baseUrl . $imagepath1 : '',
                    'imagepath2' => $imagepath2 ? $baseUrl . $imagepath2 : '',
                    'imagepath3' => $imagepath3 ? $baseUrl . $imagepath3 : '',
                ];
            });


        $imagePathsForDisease = $imagePathsData->first();

        foreach ($cropDisease as $language => $symptoms) {
            foreach ($symptoms as &$symptom) {

                if ($imagePathsForDisease) {
                    $symptom->imagepath1 = $imagePathsForDisease['imagepath1'] ?? '';
                    $symptom->imagepath2 = $imagePathsForDisease['imagepath2'] ?? '';
                    $symptom->imagepath3 = $imagePathsForDisease['imagepath3'] ?? '';
                }
            }
        }


        return response()->json($cropDisease);
    }





    public function update(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_disease_symptom_master,id',
            'symptom' => 'required|string',
            'language_cd' => 'required|exists:ag_languages_master,lang_code',
            'disease_id' => 'required|integer|exists:ag_crop_disease_master,disease_cd',
            'crop_name_id' => 'required|integer|exists:ag_crop_name_master,crop_name_cd',
            'crop_type_id' => 'required|integer|exists:ag_crop_name_master,crop_type_cd',
        ], [
            'crop_type_id.required' => 'The crop type is required.',
            'crop_name_id.required' => 'The crop name is required.',
            'disease_id.required' => 'The disease is required.',
            'language_cd.required' => 'The language is required.',
        ]);



        $symptomMapping = DB::table('ag_crop_disease_symptom_and_crop_names_mapping')
            ->where('crop_name_cd', $validated['crop_name_id'])
            ->where('disease_cd', $validated['disease_id'])
            ->first();



        if ($symptomMapping) {
            DB::table('ag_crop_disease_symptom_master')
                ->where('id', $validated['id'])
                ->update([
                    'symptom' => $validated['symptom'],
                    'language_cd' => $validated['language_cd'],
                    'crop_disease_cd' => $validated['disease_id'],
                    'symptom_id' => $symptomMapping->symptom_id,
                    'updated_at' => now()->setTimezone('Asia/Kolkata'),
                ]);

            // return redirect()->back()->with('success', 'Symptom updated successfully.');


            $updatedSymptom = DB::table('ag_crop_disease_symptom_master')
                ->where('id', $validated['id'])
                ->first();



            return response()->json([
                'success' => true,
                'message' => 'Symptom updated successfully.',
                'updatedSymptom' => $updatedSymptom,
                'cropname' => $validated['crop_name_id'],
                'croptype' => $validated['crop_type_id'],
            ]);




        } else {
            return redirect()->back()->with('error', 'No matching symptom found for the provided crop and disease.');
        }

    }

    public function destroy(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_disease_symptom_master',
        ]);

        DB::table('ag_crop_disease_symptom_master')
            ->where('id', $validated['id'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Symptom deleted successfully.',
            'id' => $validated['id']
        ]);

        // return redirect()->back()->with('success', 'Symptom deleted successfully.');

    }


    public function create(Request $request)
    {

        if ($request->isMethod('post')) {

            $validated = $request->validate([
                'symptom' => 'required|string|max:255',
                'language_cd' => 'required|string|max:255',
                'disease_cd' => 'required|integer|exists:ag_crop_disease_master,disease_cd',
                'crop_type_cd' => 'required|integer|exists:ag_crop_name_master,crop_type_cd',
                'crop_name_cd' => 'required|integer|exists:ag_crop_name_master,crop_name_cd',
            ], [
                'crop_type_cd.required' => 'The crop type is required.',
                'crop_name_cd.required' => 'The crop name is required.',
                'disease_cd.required' => 'The disease is required.',
                'language_cd.required' => 'The language is required.',
                'symptom.required' => 'Symptom is required.',
            ]);


            $symptomId = DB::table('ag_crop_disease_symptom_and_crop_names_mapping')
                ->where('crop_name_cd', $validated['crop_name_cd'])
                ->where('disease_cd', $validated['disease_cd'])
                ->value('symptom_id');

            if (!$symptomId) {
                return redirect()->back()->withErrors(['error' => 'No symptom ID found for the given crop name and disease combination.']);
            }


            $maxId = DB::table('ag_crop_disease_symptom_master')
                ->max('id');
            $nextmaxid = $maxId ? $maxId + 1 : 1;


            DB::table('ag_crop_disease_symptom_master')->insert([
                'id' => $nextmaxid,
                'symptom' => $validated['symptom'],
                'language_cd' => $validated['language_cd'],
                'symptom_id' => $symptomId,
                'crop_disease_cd' => $validated['disease_cd'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
                'created_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

            return redirect()->back()->with('success', 'Symptom created successfully.');


            // return redirect()->route('admin.cropsymptomdetails')->with('success', 'Symptom added successfully.');
        }

        // Fetch crop types for the dropdown
        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->map(function ($description) {
            return strtoupper($description); // Convert each crop type description to uppercase
        });

        // Sort the crop types alphabetically
        $cropTypes = $cropTypes->sort();
        // Handle displaying the form
        return view('admin.cropsymptomdetails.createsymptom', ['cropTypes' => $cropTypes]);

    }


}
