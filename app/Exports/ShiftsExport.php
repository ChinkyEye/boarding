<?php

namespace App\Exports;

use App\Shift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShiftsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Shift::all();
    // }

    public function view(): View
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        // dd($shifts);
        return view('backend.mainentry.shift.shift-excel', [
            'shifts' => $shifts
        ]);
    }
}
