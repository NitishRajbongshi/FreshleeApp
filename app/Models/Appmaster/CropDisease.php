<?php

namespace App\Models\Appmaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropDisease extends Model
{
    use HasFactory;
    protected $table = 'ag_crop_disease_master';
    protected $primaryKey = 'disease_cd';
    protected $guarded = [];
}

