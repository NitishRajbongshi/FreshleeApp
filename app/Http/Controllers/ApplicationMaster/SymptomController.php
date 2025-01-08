<?php

namespace App\Http\Controllers\ApplicationMaster;

use App\Http\Controllers\Controller;
use App\Models\Appmaster\CropDisease;
use App\Models\Appmaster\CropType;
use App\Models\Appmaster\CropName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SymptomController extends Controller
{

    public function index(Request $request)
    {

        $cropTypes = DB::table('ag_crop_type_master')
            ->pluck('crop_type_desc', 'crop_type_cd');

        $cropTypes = $cropTypes->toArray();

        // Sort the crop types alphabetically
        asort($cropTypes);

        // Convert all letters to uppercase
        $cropTypes = array_map(function ($description) {
            return strtoupper($description); // Convert all letters to uppercase
        }, $cropTypes);

        // Fetch diseases with crop type association
        $diseases = DB::table('ag_crop_disease_master')
            ->select('disease_cd', 'disease_name', 'crop_type_cd')
            ->get()
            ->groupBy('crop_type_cd');


        // Convert disease names to uppercase
        $diseases = $diseases->map(function ($diseaseGroup) {
            return $diseaseGroup->map(function ($disease) {
                // Convert each disease name to uppercase
                $disease->disease_name = strtoupper($disease->disease_name);
                return $disease;
            });
        });


        // Fetch symptoms data
        $symptoms = DB::table('ag_crop_disease_symptom_master as u')
            ->join('ag_languages_master as l', 'u.language_cd', '=', 'l.lang_code')
            ->join('ag_crop_disease_master as d', 'u.crop_disease_cd', '=', 'd.disease_cd')
            ->select('u.id', 'u.symptom', 'u.language_cd', 'd.disease_cd', 'l.lang_name as lang_name', 'd.disease_name', 'd.crop_type_cd')
            ->get();

        // Fetch languages
        $languages = DB::table('ag_languages_master')->pluck('lang_name', 'lang_code');

        // Return view with all the necessary data
        return view('admin.appmaster.symptom', [
            'cropTypes' => $cropTypes,
            'diseases' => $diseases,
            'symptoms' => $symptoms,
            'languages' => $languages
        ]);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_disease_symptom_master,id',
            'symptom' => 'required|string',
            'language_cd' => 'required|exists:ag_languages_master,lang_code',
            'disease_cd' => 'required|integer|exists:ag_crop_disease_master,disease_cd',
        ], [
            'disease_cd.required' => 'The disease is required.',
            'language_cd.required' => 'The language is required.',
        ]);


        DB::table('ag_crop_disease_symptom_master')
            ->where('id', $validated['id'])
            ->update([
                'symptom' => $validated['symptom'],
                'language_cd' => $validated['language_cd'],
                'crop_disease_cd' => $validated['disease_cd'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

        $cropSymptom = DB::table('ag_crop_disease_symptom_master')
            ->where('id', $validated['id'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Symptom updated successfully.',
            'cropSymptom' => $cropSymptom
        ]);

        // return redirect()->route('admin.appmaster.symptom')->with('success', 'Symptom updated successfully.');
    }


    

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'symptom' => 'required|string|max:255',
                'language_cd' => 'required|string|max:255',
                'disease_cd' => 'required|integer|exists:ag_crop_disease_master,disease_cd',
            ], [
                'disease_cd.required' => 'Disease is required.',
                'language_cd.required' => 'Language is required.',
            ]);

            $maxId = DB::table('ag_crop_disease_symptom_master')->max('id');
            $nextId = $maxId ? $maxId + 1 : 1;

            DB::table('ag_crop_disease_symptom_master')->insert([
                'id' => $nextId,
                'symptom' => $validated['symptom'],
                'crop_disease_cd' => $validated['disease_cd'],
                'language_cd' => $validated['language_cd'],
                'updated_at' => now()->setTimezone('Asia/Kolkata'),
                'created_at' => now()->setTimezone('Asia/Kolkata'),
            ]);

            return redirect()->back()->with('success', 'Symptom created successfully.');
            // return redirect()->route('admin.appmaster.symptom')->with('success', 'Symptom created successfully.');
        }


        $disease = DB::table('ag_crop_disease_master')->pluck('disease_name', 'disease_cd');
        $disease = $disease->map(function ($description) {
            return strtoupper($description);
        });
        $disease = $disease->sort();

        $languages = DB::table('ag_languages_master')->pluck('lang_name', 'lang_code');
        return view('admin.appmaster.createsymptom', ['disease' => $disease, 'languages' => $languages]);
    }


    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_disease_symptom_master,id',
        ]);

        DB::table('ag_crop_disease_symptom_master')
            ->where('id', $validated['id'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Symptom deleted successfully.',
            'id' => $validated['id']
        ]);

    }

}
