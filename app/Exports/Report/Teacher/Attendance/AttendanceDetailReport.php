<?php

namespace App\Exports\Report\Teacher\Attendance;

use App\Teacher_has_attendance;
use App\Teacher;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceDetailReport implements FromView, ShouldAutoSize,WithEvents
{
   use Exportable;

   protected $excUser,$excCurrentYear,$excCurrentMonth,$excCurrentDays;


    function __construct($excUser,$excCurrentYear,$excCurrentMonth,$excCurrentDays) {
           $this->user = $excUser;
           $this->year = $excCurrentYear;
           $this->month = $excCurrentMonth;
           $this->days = $excCurrentDays;
    }

    public function view(): View
    {
        $attendances = Teacher_has_attendance::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('user_id', $this->user);
                                                        
        $current_year = $this->year;
        $current_month = $this->month;
        $current_days = $this->days;
        $attendances = $attendances->get();
        $teachers_id = Teacher::where('user_id',$this->user)->value('id');
        $teacher_info = Teacher::find($teachers_id);
         return view('backend.report.teacher.attendance.attendance-detail-excel',compact('attendances','current_year','current_month','current_days','teacher_info'));
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

                $event->sheet->mergeCells('A3:Q3');
                $event->sheet
                      ->getStyle('A3:Q3')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(14)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A3:Q3')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A4:Q4');
                $event->sheet
                      ->getStyle('A4:Q4')
                      ->getFont()
                      ->setBold(true)
                      ->setSize(14)
                      ->setColor( new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ) );
                $event->sheet
                      ->getStyle('A4:Q4')
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);            

                // content
                $event->sheet->getStyle('A5:O5')->applyFromArray([
                    'font' => [
                        'bold' => True,
                        'size' => 12
                    ]
                ]);
                
            },
        ];
    }
}
