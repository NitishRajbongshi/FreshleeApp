<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryCategory extends Model
{
    use HasFactory;
    protected $table = 'tbl_standard_queries_categories';
    protected $primaryKey = 'catg_id';
    protected $guarded = [];

    public function _query()
    {
        return $this->hasMany(Query::class, 'query_id', 'catg_id');
    }
}
