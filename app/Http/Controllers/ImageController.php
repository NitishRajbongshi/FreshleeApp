<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class ImageController extends Controller
{
    public function show($filename)
    {
        // Define the path to your images
        DB::enableQueryLog();
        try {
            $path = storage_path($filename);
            if (!file_exists($path)) {
                Log::error('File not found:', ['path' => $path]);
                return response()->json(['message' => 'File not found.'], 404);
            }

            return response()->file($path);
        } catch (Exception $e) {
            Log::error('Error displaying image:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error displaying image.'], 500);
        }
    }
}
