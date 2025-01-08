<?php

namespace App\Http\Controllers;

use App\Models\ag_crop_image_upload;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;


class AgCropImageUploadController extends Controller
{
    //
    public function view(Request $request){
        
        //$images = ag_crop_image_upload::paginate(30);

        $i = 0;
        $files = Storage::files("/images/");
        foreach($files as $image) {
            $data['images'][$i++] = Storage::temporaryUrl($image, now()->addMinutes(30));
        }

        //$data['images'][0] = Storage::temporaryUrl('images/a (1).jpeg', now()->addMinutes(30));
        return \view('admin.filemanager.home')->with($data);
    }

   
}
