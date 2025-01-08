<?php

namespace App\Models\Appmaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropType extends Model
{
    use HasFactory;
    protected $table = 'ag_crop_type_master';
    protected $primaryKey = 'crop_type_cd';
    protected $guarded = [];
}
