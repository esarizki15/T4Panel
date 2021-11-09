<?php

namespace App\Http\Model\ContentModel;

use Illuminate\Database\Eloquent\Model;

class BusinessDirectoryGroup extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'business_directory_group';

 	 protected $fillable = [ 'bd_id', 'parent_bd_id' ];
	public function businessDirectory() {
        return $this->belongsTo('App\Http\Model\ContentModel\BusinessDirectory','bd_id','bd_id');
    }
    public function businessDirectoryParent() {
        return $this->belongsTo('App\Http\Model\ContentModel\BusinessDirectory','parent_bd_id','bd_id');
    }
}
