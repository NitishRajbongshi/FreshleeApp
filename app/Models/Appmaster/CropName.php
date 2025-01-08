<?php

namespace App\Models\Appmaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropName extends Model
{
    use HasFactory;
    protected $table = 'ag_crop_name_master';
    protected $primaryKey = 'crop_name_cd';
    protected $guarded = [];
}
