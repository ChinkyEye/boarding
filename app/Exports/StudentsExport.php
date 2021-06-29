<?php

namespace App\Exports;

use App\Student;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnWidth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;



class StudentsExport implements FromQuery,WithStyles, WithHeadings, WithMapping,ShouldAutoSize,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
     use Exportable;

    protected $excShift,$excClass,$excSection,$excBatch;

     function __construct($excShift,$excClass,$excSection,$excBatch) {
            $this->shift = $excShift;
            $this->class = $excClass;
            $this->section = $excSection;
            $this->excBatch = $excBatch;
            // if($this->shift){
            //     $this->shift_name = Shift::find($this->shift)->value('name');
            // }else{
            //     $this->shift_name = False;
            // }
            // if($this->class){
            //     $this->class_name = SClass::find($this->class)->value('name');
            // }else{
            //     $this->class_name = False;
            // }
            // if($this->section){
            //     $this->section_name = Section::find($this->section)->value('name');
            // }else{
            //     $this->section_name = False;
            // }
     }

     public function query()
     {
        // $student = Student::query()->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
        $student = Student::whereHas('getStudentViaBatch', function(Builder $query){
            $query->where('batch_id', $this->excBatch);
        })->where('school_id', Auth::user()->school_id);

        if($this->shift != NULL){
          $student = $student->where('shift_id',$this->shift);
        }
        if($this->class != NULL){
          $student = $student->where('class_id',$this->class);
        }
        if($this->section != NULL){
          $student = $student->where('section_id',$this->section);
        }
        return $student;   
     }

    public function map($student): array
        {
            return [
                $student->id,
                $student->getStudentUser->name." ".$student->getStudentUser->middle_name." ".$student->getStudentUser->last_name,
                $student->student_code,
                // $student->getBatch->name,
                $student->getStudentViaBatch->Batch->name,
                $student->roll_no,
                $student->getSection->name,
                $student->getClass->name,
                $student->getShift->name,
                $student->getStudentUser->email,
                $student->gender,
                $student->dob,
                $student->register_id,
                $student->register_date,
                $student->created_at,
                $student->getUser->name,
            ];
        }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
     
    public function  headings(): array
    {
        return 
        [
            [
                Auth::user()->getSchool->school_name,
            ],
            [
                Auth::user()->getSchool->address.", Phone: ".Auth::user()->getSchool->phone_no,
            ],
            // [
            //     "Shift: ".($this->shift_name ? $this->shift_name : 'All').
            //     ", Class: ".($this->class_name ? $this->class_name : 'All').
            //     ", Section: ".($this->section_name ? $this->section_name : 'All'),
            // ],
            [
                "ID", 
                "Full Name", 
                "Code","Batch",
                "Roll","Section",
                "Class","Shift",
                "Email","Gender","DOB",  
                "Register","Registerd Date",  
                "Created at",
                "Created By"
            ]
        ];
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
     $sheet->mergeCells('A1:Q1');
     $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
     $sheet->mergeCells('A2:Q2');
     $sheet->getStyle('A2:Q2')->getFont()->setBold(true);
     $sheet->getStyle('A3:Q3')->getFont()->setBold(true);
    }

}
