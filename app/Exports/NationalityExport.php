<?php

namespace App\Exports;

use App\Nationality;
use Maatwebsite\Excel\Concerns\FromCollection;

class NationalityExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Nationality::all();
    }
}
