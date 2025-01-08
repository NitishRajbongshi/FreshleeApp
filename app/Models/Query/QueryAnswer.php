<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryAnswer extends Model
{
    use HasFactory;
    protected $table = 'tbl_standard_query_answer';
    protected $primaryKey = 'ans_id';
    protected $guarded=[];

    public function _queryAnswer() {
        return $this->belongsTo(Query::class, 'ans_id', 'query_id');
    }
}
