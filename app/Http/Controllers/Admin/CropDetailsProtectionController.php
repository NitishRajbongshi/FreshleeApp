<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Exception;

class CropDetailsProtectionController extends Controller
{
    public function cropprotectiondetails()
    {

        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');
        $cropTypes = $cropTypes->toArray();
        asort($cropTypes);
        $cropTypes = array_map(function ($description) {
            return strtoupper($description); // Convert all letters to uppercase
        }, $cropTypes);


        $cropNames = [];

        $productTypes = DB::table('ag_crop_master_medicinal_product_type')->pluck('product_type_descr', 'product_type_cd');
        $productTypes = $productTypes->map(function ($description) {
            return strtoupper($description); // Convert each product type description to uppercase
        });

        // Sort the product types alphabetically
        $productTypes = $productTypes->sort();

        // Return view with crop types, product types, and crop names
        return view('admin.cropprotectiondetails.cropprotectiondetails', [
            'cropTypes' => $cropTypes,
            'cropNames' => $cropNames, // Pass empty crop names initially
            'productTypes' => $productTypes
        ]);
    }



    public function getCropNam(Request $request)
    {
        $cropTypeCd = $request->input('crop_type_cd');
        $cropNames = DB::table('ag_crop_name_master')
            ->where('crop_type_cd', $cropTypeCd)
            ->pluck('crop_name_desc', 'crop_name_cd');

        return response()->json($cropNames);
    }
    public function getDiseases(Request $request)
    {
        $cropNameCd = $request->input('crop_name_cd');


        $diseaseIds = DB::table('ag_crop_disease_symptom_and_crop_names_mapping')
            ->where('crop_name_cd', $cropNameCd)
            ->pluck('disease_cd');


        $diseases = DB::table('ag_crop_disease_master')
            ->whereIn('disease_cd', $diseaseIds)
            ->pluck('disease_name', 'disease_cd');

        return response()->json($diseases);

    }



    public function getTechnicalNameByProductType(Request $request)
    {

        $validated = $request->validate([
            'product_type_cd' => 'required|integer|exists:ag_crop_master_medicinal_product_type,product_type_cd',
        ]);

        $technicalCodes = DB::table('ag_crop_master_medicinal_products')
            ->where('product_type_cd', $validated['product_type_cd'])
            ->select('technical_code', 'technical_name')
            ->get()
            ->unique('technical_code')
            ->values()
            ->all();

        return response()->json($technicalCodes);
    }

    public function getTradeNamesByTechnicalCodes(Request $request)
    {

        $validated = $request->validate([
            'technical_code' => 'required|string|exists:ag_crop_master_medicinal_products,technical_code',
        ]);

        $tradeNames = DB::table('ag_crop_master_medicinal_products')
            ->where('technical_code', $validated['technical_code'])
            ->where('status', 'A')
            ->where('is_registered', 'Y')
            ->select('trade_name', 'trade_code')
            ->get();

        return response()->json($tradeNames);
    }




    public function getCropDisease(Request $request)
    {
        $diseaseNameCd = $request->input('disease_cd');
        $cropNameCd = $request->input('crop_name_cd');


        $cropDisease = DB::table('ag_crop_master_disease_n_crop_name_n_control_measure_mapping')
            ->where('disease_cd', $diseaseNameCd)
            ->where('crop_name_cd', $cropNameCd)
            ->select('mapping_id', 'disease_cd', 'crop_name_cd', 'control_measure', 'control_measure_as', 'imagepath1', 'imagepath2', 'imagepath3', 'tech_key_words_and_codes')
            ->get()
            ->map(function ($item) {
                $baseUrl = env('APP_URL') . '/external_images';

                $rootPath = config('filesystems.disks.external.root');

                $imagepath1 = $item->imagepath1 ? str_replace($rootPath, '', $item->imagepath1) : '';
                $imagepath2 = $item->imagepath2 ? str_replace($rootPath, '', $item->imagepath2) : '';
                $imagepath3 = $item->imagepath3 ? str_replace($rootPath, '', $item->imagepath3) : '';

                return [
                    'mapping_id' => $item->mapping_id,
                    'disease_cd' => $item->disease_cd ?? '',
                    'crop_name_cd' => $item->crop_name_cd ?? '',
                    'control_measure' => $item->control_measure ?? '',
                    'control_measure_as' => $item->control_measure_as ?? '',
                    'imagepath1' => $imagepath1 ? $baseUrl . $imagepath1 : '',
                    'imagepath2' => $imagepath2 ? $baseUrl . $imagepath2 : '',
                    'imagepath3' => $imagepath3 ? $baseUrl . $imagepath3 : '',
                    'tech_key_words_and_codes' => $item->tech_key_words_and_codes ?? '',
                ];


            });



        return response()->json($cropDisease);
    }




    public function saveCropProtectionDetails(Request $request)
    {

        $validated = $request->validate([
            'control_measure' => 'required|string',
            'control_measure_as' => 'nullable|string',
            'crop_name_cd' => 'required|exists:ag_crop_name_master,crop_name_cd',
            'disease_cd' => 'required|integer|exists:ag_crop_disease_master,disease_cd',
            'imagepath1' => 'nullable|file|mimes:jpeg,png,jpg',
            'imagepath2' => 'nullable|file|mimes:jpeg,png,jpg',
            'imagepath3' => 'nullable|file|mimes:jpeg,png,jpg',
            'tech_key_words_and_codes' => 'nullable|string|max:955',
        ]);

        $cropNameCd = $validated['crop_name_cd'];
        $diseaseCd = $validated['disease_cd'];

        $existingRecord = DB::table(table: 'ag_crop_master_disease_n_crop_name_n_control_measure_mapping')
            ->where('crop_name_cd', $cropNameCd)
            ->where('disease_cd', $diseaseCd)
            ->first();


            if ($existingRecord) {

                $updateData = [
                    'control_measure' => $validated['control_measure'],
                    'control_measure_as' => $validated['control_measure_as'],
                    'disease_cd' => $validated['disease_cd'],
                    'crop_name_cd' => $validated['crop_name_cd'],
                    'tech_key_words_and_codes' => $validated['tech_key_words_and_codes'],
                    'updated_at' => now()->setTimezone('Asia/Kolkata'),
                ];


                $folderPath = dirname($existingRecord->imagepath1);


                $rootPath = config('filesystems.disks.external.root');
                $folderPath = str_replace($rootPath, '', $folderPath);


                if (!Storage::disk('external')->exists($folderPath)) {
                    Storage::disk('external')->makeDirectory($folderPath);
                }

                Log::info($folderPath);


                if ($request->hasFile('imagepath1')) {
                    $image1 = $request->file('imagepath1');
                    $imageName1 = basename($existingRecord->imagepath1);
                    $image1->storeAs($folderPath, $imageName1, 'external');
                    $updateData['imagepath1'] = $rootPath . $folderPath . '/' . $imageName1;
                } else {
                    $updateData['imagepath1'] = $existingRecord->imagepath1;

                }


                if ($request->hasFile('imagepath2')) {
                    $image2 = $request->file('imagepath2');
                    $imageName2 = basename($existingRecord->imagepath2);
                    $image2->storeAs($folderPath, $imageName2, 'external');
                    $updateData['imagepath2'] = $rootPath . $folderPath . '/' . $imageName2; // Update the database path with the new image location
                } else {
                    $updateData['imagepath2'] = $existingRecord->imagepath2;

                }

                if ($request->hasFile('imagepath3')) {
                    $image3 = $request->file('imagepath3');
                    $imageName3 = basename($existingRecord->imagepath3);
                    $image3->storeAs($folderPath, $imageName3, 'external');
                    $updateData['imagepath3'] = $rootPath . $folderPath . '/' . $imageName3; // Update the database path with the new image location
                } else {
                    // If no new image is uploaded, retain the old image path
                    $updateData['imagepath3'] = $existingRecord->imagepath3;
                }


                DB::table('ag_crop_master_disease_n_crop_name_n_control_measure_mapping')
                ->where('disease_cd', $diseaseCd)
                ->where('crop_name_cd', $cropNameCd)
                    ->update($updateData);
            }
            else{
                    $disease = DB::table('ag_crop_disease_master')
                        ->where('disease_cd', $validated['disease_cd'])
                        ->first();

                    $crop = DB::table('ag_crop_name_master')
                        ->where('crop_name_cd', $validated['crop_name_cd'])
                        ->first();


                    if (!$disease) {
                        return redirect()->back()->withErrors(['disease_cd' => 'Disease not found.']);
                    }

                    $cropRegistryNo = $crop->crop_registry_no ?: str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

                    $folderName = strtoupper($disease->disease_name) . '_' . $cropRegistryNo;


                    $folderPath = $folderName;


                    if (!Storage::disk('external')->exists($folderPath)) {
                        Storage::disk('external')->makeDirectory($folderPath);
                    }

                    Log::info($folderPath);


                    $image1 = $request->file('imagepath1');
                    $image2 = $request->file('imagepath2');
                    $image3 = $request->file('imagepath3');

                    // Generate unique names for the images (optional, using timestamps or random values)
                    $imageName1 = $folderName . '_' . substr(uniqid(), -7) . '.' . $image1->getClientOriginalExtension();
                    $imageName2 = $folderName . '_' . substr(uniqid(), -7) . '.' . $image2->getClientOriginalExtension();
                    $imageName3 = $folderName . '_' . substr(uniqid(), -7) . '.' . $image3->getClientOriginalExtension();


                    // Store the images in the folder
                    $image1->storeAs($folderPath, $imageName1, 'external');
                    $image2->storeAs($folderPath, $imageName2, 'external');
                    $image3->storeAs($folderPath, $imageName3, 'external');

                    $rootPath = config('filesystems.disks.external.root');

                    // Get the next mapping ID
                    $maxId = DB::table('ag_crop_master_disease_n_crop_name_n_control_measure_mapping')
                        ->max('mapping_id');
                    $nextmaxid = $maxId ? $maxId + 1 : 1;

                    // Insert data into the database with the new image names as strings
                    DB::table('ag_crop_master_disease_n_crop_name_n_control_measure_mapping')->insert([
                        'mapping_id' => $nextmaxid,
                        'control_measure' => $validated['control_measure'],
                        'control_measure_as' => $validated['control_measure_as'],
                        'crop_name_cd' => $cropNameCd,
                        'disease_cd' => $diseaseCd,
                        'imagepath1' => $rootPath . '/' . $folderPath . '/' . $imageName1,
                        'imagepath2' => $rootPath . '/' . $folderPath . '/' . $imageName2,
                        'imagepath3' => $rootPath . '/' . $folderPath . '/' . $imageName3,
                        'tech_key_words_and_codes' => $validated['tech_key_words_and_codes'],
                        'updated_at' => now()->setTimezone('Asia/Kolkata'),
                        'created_at' => now()->setTimezone('Asia/Kolkata'),
                    ]);
                }

        return response()->json(['success' => 'All details are inserted successfully.']);
    }
}
