<?php

namespace App\Exports\Report\Teacher\Salary;

use App\Teacher;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class SalaryExport implements FromView, ShouldAutoSize,WithEvents
{
    
    use Exportable;

    protected $excMonth;


    function __construct($excMonth) {
    	$this->month = $excMonth;
    }

    public function view(): View
    {
        $nepali_month = $this->month;
        $teachers_list = Teacher::where('school_id', Auth::user()->school_id)
                                ->whereHas('getTeacherFromBatch', function(Builder $query){
                                    $query->where('batch_id', Auth::user()->batch_id);
                                })
                                  // ->where('batch_id', Auth::user()->batch_id)
                                  ->with(['getTeacherIncome' => function ($query) use ($nepali_month) {
                                         $query->where('school_id', Auth::user()->school_id)
                                               ->where('batch_id', Auth::user()->batch_id)
                                               ->where('month', $nepali_month);
                                     }]);
         $teachers_list = $teachers_list->get();
        
        return view('backend.report.teacher.salary.salary-excel', [
            'teachers_list' => $teachers_list
        ]);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                $event->writer->setCreator('Techware School Management System');
            },
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet
                      ->getPageSetup()
                      ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                // header
                $event->sheet->mergeCells('A1:Q1');
                $event->sheet
                      ->getStyle('A1:Q1')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(16)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A1:Q1')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A2:Q2');
                $event->sheet
                      ->getStyle('A2:Q2')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(14)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A2:Q2')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // content
                $event->sheet->getStyle('A3:O3')->applyFromArray([
                    'font' => [
                        'bold' => True,
                        'size' => 12
                    ]
                ]);
                
            },
        ];
    }
}
