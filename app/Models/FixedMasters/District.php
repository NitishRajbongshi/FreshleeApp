<?php

namespace App\Models\FixedMasters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'tbl_district_master';
    protected $primaryKey = 'district_id';
    protected $guarded = [];
}
