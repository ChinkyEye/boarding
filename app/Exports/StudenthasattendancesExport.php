<?php

namespace App\Exports;

use App\Student_has_attendance;
use App\Shift;
use App\SClass;
use App\Section;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class StudenthasattendancesExport implements FromView,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $excShift,$excClass,$excSection,$excDate;

    function __construct($excShift,$excClass,$excSection,$excDate) {
    	$this->shift = $excShift;
    	$this->class = $excClass;
    	$this->section = $excSection;
    	$this->date = $excDate;
    }

    public function view(): View
    {
    	$studentattendances = Student_has_attendance::orderBy('id','ASC')->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
        $shift_data = $this->shift;
        $class_data = $this->class;
        $section_data = $this->section;
        
    	if($shift_data != NULL && $class_data != NULL && $section_data != NULL ){
          $studentattendances = $studentattendances->whereHas('getAttendStudentList', function (Builder $query) use ($shift_data,$class_data,$section_data) {
                    $query->where('shift_id', $shift_data);
                    $query->where('class_id', $class_data);
                    $query->where('section_id', $section_data);
                });

    	}

    	if($this->date != NULL){
    	  $studentattendances = $studentattendances->where('date',$this->date);
    	}
        $all_student_attend = $studentattendances->get();
        $shift = Shift::find($this->shift);
        $class = SClass::find($this->class);
        $section = Section::find($this->section);
        return view('backend.attendance.Studenthasattendance.student-excel', [
            'studentattendances' => $all_student_attend,
            'shift' => $shift,
            'class' => $class,
            'section' => $section,
            'date' => $this->date,
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

                // content
                $event->sheet->getStyle('A4:P4')->applyFromArray([
                    'font' => [
                        'bold' => True,
                        'size' => 12
                    ]
                ]);
                
            },
        ];
    }
    
}
