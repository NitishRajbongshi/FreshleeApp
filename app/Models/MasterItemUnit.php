<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItemUnit extends Model
{
    use HasFactory;
    protected $table ='smartag_market.master_item_units';
    protected $fillable = ['unit_cd', 'unit_desc'];
}
