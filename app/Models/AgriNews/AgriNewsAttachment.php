<?php

namespace App\Models\AgriNews;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriNewsAttachment extends Model
{
    use HasFactory;
    protected $table = 'tbl_agrinews_attachment_dtls';
    protected $guarded =[];
}
