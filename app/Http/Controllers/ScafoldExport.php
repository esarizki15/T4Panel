<?php 
namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScafoldExport implements FromView
{
	var $items ;
	var $columns ;
	public function setItems($items=null) {
    	$this->items = $items;
    }
    
     public function setColumns($columns=null) {
    	$this->columns = $columns;
    }

    public function view(): View
    {
    	$items = $this->items;
    	$columns = $this->columns;

        return view('scafold-bundle.export',compact('items','columns') );
    }

   
}