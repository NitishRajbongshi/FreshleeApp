<?php

namespace App\Models\FixedMasters;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'tbl_state_master';
    protected $primaryKey = 'state_id';
    protected $guarded = [];
}

