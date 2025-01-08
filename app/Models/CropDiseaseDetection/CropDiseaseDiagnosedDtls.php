<?php

namespace App\Models\CropDiseaseDetection;

use App\Models\FixedMasters\District;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Appmaster\CropDisease;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropDiseaseDiagnosedDtls extends Model
{
    use HasFactory;
    protected $table = 'ag_disease_diagnose_dtls';
    protected $primaryKey = 'image_id';
    protected $guarded = [];
    
    public function cropDisease() 
    {
        return $this->hasMany(CropDisease::class, 'disease_cd', 'disease_cd');
    }

    public function district()
    {
        return $this->hasMany(District::class, 'district_cd', 'district_cd');
    }
    public function user() 
    {
        return $this->hasOne(User::class, 'requested_by', 'user_id');
    }
}
