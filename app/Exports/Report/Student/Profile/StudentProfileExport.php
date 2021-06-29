<?php

namespace App\Exports\Report\Student\Profile;

use Maatwebsite\Excel\Concerns\FromCollection;
use Auth;
use App\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class StudentProfileExport implements FromView, ShouldAutoSize,WithEvents
{
  use Exportable;

  protected $excShiftData,$excClassData,$excSectionData;


   function __construct($excShiftData,$excClassData,$excSectionData) {
          $this->shift = $excShiftData;
          $this->class = $excClassData;
          $this->section = $excSectionData;
   }

   public function view(): View
   {
       $students_list = Student::where('school_id', Auth::user()->school_id)
                                 ->whereHas('getStudentViaBatch', function(Builder $query){
                                    $query->where('batch_id', Auth::user()->batch_id);
                                });
                               // ->where('batch_id', Auth::user()->batch_id);
       // if($this->code != NULL){
       //   $teachers_list = $teachers_list->where('teacher_code',$this->code);
       // }
                               // dd($this->shift);
       if($this->shift != NULL){
           $students_list = $students_list->where('shift_id', $this->shift);
       }

       if($this->class != NULL){
           $students_list = $students_list->where('class_id', $this->class);
       }

       if($this->section != NULL){
           $students_list = $students_list->where('section_id', $this->section);
       }

       $students_list = $students_list->with('getStudentUser')
                               ->with('getUser')
                               ->get();
       return view('backend.report.student.profile.profile-excel', [
           'students_list' => $students_list
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
}
