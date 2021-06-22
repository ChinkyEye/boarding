<?php

namespace App\Exports;

use App\Teacher;
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

class TeachersExport implements FromQuery,WithStyles, WithHeadings, WithMapping,ShouldAutoSize,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $excShift,$excClass,$excSection;

     function __construct($excShift,$excClass,$excSection) {
            $this->shift = $excShift;
            $this->class = $excClass;
            $this->section = $excSection;
     }

     public function query()
     {
        $teacher = Teacher::query()->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
        if($this->shift != NULL){
            $shift_data = $this->shift;
            $teacher = $teacher->whereHas('getShiftTeacherManyList', function (Builder $query) use ($shift_data) {
                $query->where('shift_id', $shift_data);
            });
        }
        if($this->class != NULL){
            $class_data = $this->class;
            $teacher = $teacher->whereHas('getTeacherPeriod', function (Builder $query) use ($class_data) {
                $query->where('class_id', $this->class);
            });
        }
        if($this->section != NULL){
            $section_data = $this->section;
            $teacher = $teacher->whereHas('getTeacherPeriod', function (Builder $query) use ($section_data) {
                $query->where('section_id', $this->section);
            });
        }
        return $teacher;   
     }

     public function map($teacher): array
         {
             return [
                 $teacher->id,
                 $teacher->getTeacherUser->name." ".$teacher->getTeacherUser->middle_name." ".$teacher->getTeacherUser->last_name,
                 $teacher->uppertype,
                 $teacher->cinvestment_id,
                 $teacher->pan_id,
                 $teacher->teacher_code,
                 $teacher->qualification,
                 $teacher->getTeacherUser->email,
                 $teacher->gender,
                 $teacher->dob,
                 $teacher->phone,
                 $teacher->designation,
                 $teacher->created_at,
                 $teacher->getUser->name,
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
            [
                "ID", 
                "Full Name", 
                "Type",
                "Citizenship","Pan",
                "Teacher Code","Qualification",
                "Email","Gender","DOB",  
                "Phone","Designation",  
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
                $event->sheet->getStyle('A3:N3')->applyFromArray([
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
