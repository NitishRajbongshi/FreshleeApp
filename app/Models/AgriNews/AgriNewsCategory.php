<?php

namespace App\Models\AgriNews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriNewsCategory extends Model
{
    use HasFactory;
    protected $table = "tbl_agrinews_category_master";
    protected $guarded = [];
}
