<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;
    protected $table = 'tbl_standard_queries';
    protected $primaryKey = 'query_id';
    protected $guarded = [];

    public function queryCategory() 
    {
        return $this->belongsTo(QueryCategory::class, 'query_id', 'catg_id');
    }

    public function queryAttachment() 
    {
        return $this->hasMany(QueryAttachment::class, 'query_id', 'id');
    }
    public function queryAnswer() 
    {
        return $this->hasMany(QueryAnswer::class, 'query_id', 'ans_id');
    }
}
