<?php

namespace App\Http\Controllers\Admin;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class AgriMedicianlProductController extends Controller
{

    public function agriMedicinalProducts()
    {

        $productTypes = DB::table('ag_crop_master_medicinal_product_type')->pluck('product_type_descr', 'product_type_cd');
        $productTypes = $productTypes->map(function ($description) {
            return strtoupper($description);
        });


        $productTypes = $productTypes->sort();

        $cropmeds = DB::table('ag_crop_master_medicinal_products as u')
            ->join('ag_crop_master_medicinal_product_type as d', 'u.product_type_cd', '=', 'd.product_type_cd')
            ->join('ag_crop_master_medicinal_product_status as s', 'u.status', '=', 's.status_code')
            ->select('u.id', 'u.technical_code', 'u.technical_name', 'u.trade_code', 'u.trade_name', 'u.product_type_cd', 'u.manufacturer_name', 'u.status','u.is_registered', 'u.image_file_path')
            ->get();


        $baseUrl = env('APP_URL') . '/external_image';
        $rootPath = config('filesystems.disks.external2.root');

        $cropmeds = $cropmeds->map(function ($item) use ($baseUrl, $rootPath) {
            $imageFilePath = $item->image_file_path ? str_replace($rootPath, '', $item->image_file_path) : '';

            return [
                'id' => $item->id,
                'technical_code' => $item->technical_code,
                'technical_name' => $item->technical_name,
                'trade_code' => $item->trade_code,
                'trade_name' => $item->trade_name,
                'product_type_cd' => $item->product_type_cd,
                'manufacturer_name' => $item->manufacturer_name,
                'status' => $item->status,
                'is_registered' => $item->is_registered,
                'image_file_path' => $imageFilePath ? $baseUrl . $imageFilePath : '',
            ];
        });


        $productStatus = DB::table('ag_crop_master_medicinal_product_status')->pluck('status_descr', 'status_code');
        $productStatus = $productStatus->map(function ($description) {
            return strtoupper($description);
        });

        $productStatus = $productStatus->sort();

        return view('admin.agriMedicinalProducts.agriMedicinalProducts', [
            'productTypes' => $productTypes,
            'cropmeds' => $cropmeds,
            'productStatus' => $productStatus
        ]);
    }


public function create(Request $request)
    {
        if ($request->isMethod('post')) {

            try {
                $validated = $request->validate([
                    'technical_code' => 'required|string|max:255',
                    'technical_name' => 'required|string|max:255',
                    'trade_code' => 'required|string|max:255',
                    'trade_name' => 'required|string|max:255',
                    'product_type_cd' => 'required|integer|exists:ag_crop_master_medicinal_product_type,product_type_cd',
                    'manufacturer_name' => 'required|string|max:255',
                    'image_file_path' => 'required|file|mimes:jpeg,png,jpg',
                    'status' => 'required|string|exists:ag_crop_master_medicinal_product_status,status_code',
                    'is_registered' => 'required|string|max:255',
                ]);

                $rootPath = config('filesystems.disks.external2.root');

                $imageFile = $request->file('image_file_path');
                $imageName = 'IMG' . '-' . substr(uniqid(), -7) . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->storeAs( '',$imageName, 'external2');



                $imageFilePath = $rootPath . '/' . $imageName;


                $maxId = DB::table('ag_crop_master_medicinal_products')->max('id');
                $nextmaxid = $maxId ? $maxId + 1 : 1;



                DB::table('ag_crop_master_medicinal_products')->insert([
                    'id' => $nextmaxid,
                    'technical_code' => $validated['technical_code'],
                    'technical_name' => $validated['technical_name'],
                    'trade_code' => $validated['trade_code'],
                    'trade_name' => $validated['trade_name'],
                    'product_type_cd' => $validated['product_type_cd'],
                    'manufacturer_name' => $validated['manufacturer_name'],
                    'image_file_path' => $imageFilePath,
                    'updated_at' => now()->setTimezone('Asia/Kolkata'),
                    'created_at' => now()->setTimezone('Asia/Kolkata'),
                    'created_by' => 'admin',
                    'status' => $validated['status'],
                    'is_registered' => $validated['is_registered'],
                ]);

                return redirect()->back()->with('success', 'Crop Medicinal product created successfully.');

            } catch (\Exception $e) {
                // Log the error
                \Log::error('Error creating Crop Medicinal product: ' . $e->getMessage(), [
                    'error' => $e->getTraceAsString(),
                ]);

                return redirect()->back()->with('error', 'An error occurred while creating the Crop Medicinal product.');
            }
        }

        $productTypes = DB::table('ag_crop_master_medicinal_product_type')->pluck('product_type_descr', 'product_type_cd');
        $productTypes = $productTypes->map(function ($description) {
            return strtoupper($description); // Convert each product type description to uppercase
        });

        $productTypes = $productTypes->sort();

        $productStatus = DB::table('ag_crop_master_medicinal_product_status')->pluck('status_descr', 'status_code');
        $productStatus = $productStatus->map(function ($description) {
            return strtoupper($description);
        });

        $productStatus = $productStatus->sort();

        return view('admin.agriMedicinalProducts.createmedicinalproducts', [
            'productTypes' => $productTypes,
            'productStatus' => $productStatus
        ]);
    }

    public function destroy(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required|integer|exists:ag_crop_master_medicinal_products',
        ]);

        DB::table('ag_crop_master_medicinal_products')
            ->where('id', $validated['id'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Crop medicinal product deleted successfully.',
            'id' => $validated['id']
        ]);
    }

    public function update(Request $request)
    {

            $validated = $request->validate([
                'id' => 'required|integer|exists:ag_crop_master_medicinal_products,id',
                'product_type_cd' => 'required|integer|exists:ag_crop_master_medicinal_product_type,product_type_cd',
                'status' => 'required|string|exists:ag_crop_master_medicinal_product_status,status_code',
                'is_registered' => 'required|string',
                'technical_code' => 'required|string',
                'technical_name' => 'required|string',
                'trade_code' => 'required|string',
                'trade_name' => 'required|string',
                'manufacturer_name' => 'required|string|max:255',
                'status_reason' => 'required|string',
                'registered_reason' => 'required|string',
                'image_file_path' => 'nullable|file|mimes:jpeg,png,jpg',

            ]);

        $existingRecord = DB::table('ag_crop_master_medicinal_products')
            ->where('id', $validated['id'])
            ->first();

        $historyData = [
            'id' => $existingRecord->id,
            'technical_code' => $existingRecord->technical_code,
            'technical_name' => $existingRecord->technical_name,
            'trade_code' => $existingRecord->trade_code,
            'trade_name' => $existingRecord->trade_name,
            'product_type_cd' => $existingRecord->product_type_cd,
            'manufacturer_name' => $existingRecord->manufacturer_name,
            'status' => $existingRecord->status,
            'is_registered' => $existingRecord->is_registered,
            'status_reason' => $validated['status_reason'],
            'registered_reason' => $validated['registered_reason'],
            'image_file_path' => $existingRecord->image_file_path,
            'updated_at' => now()->setTimezone('Asia/Kolkata'),
            'created_by' => $existingRecord->created_by,
        ];

        if ($request->hasFile('image_file_path')) {
            $rootPath = config('filesystems.disks.external2.root');
            $referenceFolderPath = 'D:/SmartAg_Appl_Files/agri_medicine_image_reference_hist';
            $oldImageName = basename($existingRecord->image_file_path);
            $newImagePath = $referenceFolderPath . '/' . $oldImageName;


            if (!file_exists($referenceFolderPath)) {
                mkdir($referenceFolderPath, 0777, true);
            }


            rename($existingRecord->image_file_path, $newImagePath);
            $historyData['image_file_path'] = $newImagePath;
        }

        DB::table('ag_crop_master_medicinal_products_history')->updateOrInsert(
            ['id' => $existingRecord->id],
            $historyData
        );

        $updateData = [
            'product_type_cd' => $validated['product_type_cd'],
            'status' => $validated['status'],
            'is_registered' => $validated['is_registered'],
            'trade_code' => $validated['trade_code'],
            'trade_name' => $validated['trade_name'],
            'technical_code' => $validated['technical_code'],
            'technical_name' => $validated['technical_name'],
            'manufacturer_name' => $validated['manufacturer_name'],
            'updated_at' => now()->setTimezone('Asia/Kolkata'),
        ];

        if ($request->hasFile('image_file_path')) {

            $randomImageName = 'IMG-' . uniqid() . '.' . $request->file('image_file_path')->getClientOriginalExtension();
            $destinationPath = $rootPath . '/' . $randomImageName;

            if (file_exists($existingRecord->image_file_path)) {
                $referenceFolderPath = 'D:/SmartAg_Appl_Files/agri_medicine_image_reference_hist';

                if (!file_exists($referenceFolderPath)) {
                    mkdir($referenceFolderPath, 0777, true);
                }

                $oldImageName = basename($existingRecord->image_file_path);
                $newImagePath = $referenceFolderPath . '/' . $oldImageName;
                rename($existingRecord->image_file_path, $newImagePath);
            }

            $request->file('image_file_path')->move($rootPath, $randomImageName);
            $updateData['image_file_path'] = $destinationPath;

        } else {
             $updateData['image_file_path'] = $existingRecord->image_file_path;
        }

        $baseUrl = env('APP_URL') . '/external_image';

        DB::table('ag_crop_master_medicinal_products')
        ->where('id', $validated['id'])
        ->update($updateData);

        $updatedMedicinalProduct = DB::table('ag_crop_master_medicinal_products')
            ->where('id', $validated['id'])
            ->first();

        $imageFilePath = $updatedMedicinalProduct->image_file_path
        ? str_replace($rootPath, '', $updatedMedicinalProduct->image_file_path)
        : '';



        return response()->json([
            'success' => true,
            'message' => 'Medicinal Product updated successfully.',
            'updatedMedicinalProduct' => [
                    'id' => $updatedMedicinalProduct->id,
                    'technical_code' => $updatedMedicinalProduct->technical_code,
                    'technical_name' => $updatedMedicinalProduct->technical_name,
                    'trade_code' => $updatedMedicinalProduct->trade_code,
                    'trade_name' => $updatedMedicinalProduct->trade_name,
                    'product_type_cd' => $updatedMedicinalProduct->product_type_cd,
                    'manufacturer_name' => $updatedMedicinalProduct->manufacturer_name,
                    'status' => $updatedMedicinalProduct->status,
                    'is_registered' => $updatedMedicinalProduct->is_registered,
                    'image_file_path' => $imageFilePath ? $baseUrl . $imageFilePath : '',
                ]

        ]);
    }
}
