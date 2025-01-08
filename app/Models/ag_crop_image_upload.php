<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ag_crop_image_upload extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_path'
    ];
}
