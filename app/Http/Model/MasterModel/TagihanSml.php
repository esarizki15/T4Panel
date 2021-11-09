<?php

namespace App\Http\Model\MasterModel;

use Illuminate\Database\Eloquent\Model;

class TagihanSml extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'tagihan_sml';

 	 protected $fillable = [ 'tahun_bulan','no','ipl','tagihan_air','denda_air','tagihan_ipl','denda_ipl','status','total_tagihan','tgl_bayar','ket_bayar','kdunit','nama_lang','nopel','pemakaian_air'  ];

   
}
