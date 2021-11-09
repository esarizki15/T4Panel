<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'kategori_id';
    protected $table = 'kategori';

 	 protected $fillable = [  'kategori_nm','kategori_status','kategori_photo','kategori_desc','kategori_parent','kategori_level','kategori_jns','kategori_image' ];
   
}
