<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CropDiseaseDetection\CropDiseaseDiagnosedDtls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use stdClass;

class AppLinkDeleteUser extends Controller
{
   public function deleteUser()
   {
      return 'Welcome To Delete User Page';
   }
}