<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryReject extends Model
{
    use HasFactory;
    protected $table = 'tbl_standard_queries_reject_reason';
    protected $guarded = [];

}
