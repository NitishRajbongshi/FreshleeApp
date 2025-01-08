<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAttachment extends Model
{
    use HasFactory;
    protected $table = 'tbl_standard_queries_attachment';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function _queryAttachment()
    {
        return $this->belongsTo(Query::class,'query_id','id');
    }
}
