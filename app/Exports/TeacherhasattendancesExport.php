<?php

namespace App\Exports;

use App\Teacher_has_attendance;
use App\Shift;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithColumnWidth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TeacherhasattendancesExport implements FromView, WithHeadings,ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $excShift,$excDate;

    function __construct($excShift,$excDate) {
    	$this->shift = $excShift;
    	$this->date = $excDate;
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Created at',
            'Updated at'
        ];
    }

   

    public function view(): View
    {
    	// dd($this);
    	$teacherattendances = Teacher_has_attendance::orderBy('id','ASC')->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);

    	if($this->shift != NULL){
    		$shift_data = $this->shift;
    		$teacherattendances = $teacherattendances->whereHas('getAttendShiftTeacherList', function (Builder $query) use ($shift_data) {
    			$query->where('shift_id', $shift_data);
    		});
    	}

    	if($this->date != NULL){
            // dd($this->date);
    	  $teacherattendances = $teacherattendances->where('date',$this->date);
    	}
    	$shift_id = Shift::find($this->shift);
    	$all_teacher_attend = $teacherattendances->get();
        return view('backend.attendance.teacher_has_attendance.teacher-excel', [
            'teacherattendances' => $all_teacher_attend,
            'shift' => $shift_id,

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
               $event->sheet->getStyle('A3:P3')->applyFromArray([
                   'font' => [
                       'bold' => True,
                       'size' => 12
                   ]
               ]);
               
           },
       ];
   }
   

   

    public function styles(Worksheet $sheet)
    {
     $sheet->getStyle('B1')->getFont()->setBold(true);
    }
    
}
