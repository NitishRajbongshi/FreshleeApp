<?php

namespace App\Models\AgriNews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriNews extends Model
{
    use HasFactory;
    protected $table = "tbl_agrinews_dtls";
    protected $guarded = [];
}
