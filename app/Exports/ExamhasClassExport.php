<?php

namespace App\Exports;

use App\Examhasclass;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExamhasClassExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    function __construct($id) {
    	$this->id = $id;
    }
    public function collection()
    {
        return Examhasclass::where('id',$this->id)->get();
    }
}
