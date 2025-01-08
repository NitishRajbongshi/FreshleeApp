<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageController; // Include the ImageController
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewCropImageController extends Controller
{
    public function reviewcropimage()
    {
        $data = DB::table('ag_disease_diagnose_dtls_manually as u')
            ->join('ag_crop_disease_master as c', 'u.disease_cd', '=', 'c.disease_cd')
            ->join('users as n', 'u.requested_by', '=', 'n.user_id')
            ->select('c.disease_name', 'u.user_uploaded_image_path', 'u.user_selected_image_path', 'n.name')
            ->get()
            ->map(function ($item) {
                $item->user_uploaded_image_path = route('image.show', ['filename' => $item->user_uploaded_image_path]);
                $item->user_selected_image_path = route('image.show', ['filename' => $item->user_selected_image_path]);
                return $item;
            });

        $cropTypes = DB::table('ag_crop_type_master')->pluck('crop_type_desc', 'crop_type_cd');

        $diseases = DB::table('ag_crop_disease_master')
            ->select('disease_cd', 'disease_name', 'crop_type_cd')
            ->get()
            ->groupBy('crop_type_cd');

        $diseasesData = [];
        foreach ($diseases as $cropTypeCd => $diseaseGroup) {
            $diseasesData[$cropTypeCd] = $diseaseGroup->pluck('disease_name', 'disease_cd')->toArray();
        }

        return view('admin.reviewcropimage.reviewcropimage', [
            'data' => $data,
            'cropTypes' => $cropTypes,
            'diseases' => $diseasesData,
        ]);
    }

    public function saveCorrectImage(Request $request)
    {
        ini_set('max_execution_time', 180);
        $request->validate([
            'disease_name' => 'required|string',
            'user_uploaded_image_path' => 'required|string',
        ]);

        $diseaseName = $request->disease_name;
        $userUploadedImagePath = $request->user_uploaded_image_path;

        // Decode the URL properly
        $userUploadedImage = urldecode($userUploadedImagePath);
        Log::info('Incoming request to save correct image:', [
            'disease_name' => $diseaseName,
            'user_uploaded_image_path' => $userUploadedImagePath,
        ]);

        // Clean the path again just in case
        $userUploadedImage = str_replace('\\', '/', $userUploadedImage);

        // Adjust the logic to extract the image filename correctly
        $filename = basename($userUploadedImage);

        // Construct the source image path
        $sourceImagePath = "C:/Users/vidhi/phpProjects/agriWebSiteProj/agriWebSiteProj/storage/SmartAg_Appl_Files/imagesDiagonsedByManually/{$filename}";

        // Directory for saving images
        $storagePath = "D:/SmartAg_Appl_Files/{$diseaseName}";

        // Create directory if it doesn't exist
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
            Log::info('Created directory: ' . $storagePath);
        }

        $destinationPath = "{$storagePath}/{$filename}";
        Log::info('filename: ' . $filename);
        Log::info('destinationPath: ' . $destinationPath);

        // Copy the image instead of downloading
        try {
            // Copy the image content to the destination path
            if (copy($sourceImagePath, $destinationPath)) {
                Log::info('Image copied successfully:', ['path' => $destinationPath]);
                return redirect()->route('admin.reviewcropimage')->with('success', 'Image saved successfully!');
            } else {
                Log::error('Failed to copy image:', ['source' => $sourceImagePath, 'destination' => $destinationPath]);
                return redirect()->back()->with('error', 'Failed to save the image.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to save the image:', ['error' => $e->getMessage(), 'url' => $userUploadedImagePath]);
            return redirect()->back()->with('error', 'Failed to save the image.');
        }
    }

}
