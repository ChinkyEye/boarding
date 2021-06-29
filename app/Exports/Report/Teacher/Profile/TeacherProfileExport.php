<?php

namespace App\Exports\Report\Teacher\Profile;

use App\Teacher;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;



class TeacherProfileExport implements FromView, ShouldAutoSize,WithHeadings,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    protected $excTeacherCode,$excShiftData;


     function __construct($excTeacherCode,$excShiftData) {
            $this->code = $excTeacherCode;
            $this->shift = $excShiftData;
     }

    public function view(): View
    {
        $teachers_list = Teacher::where('school_id', Auth::user()->school_id)
                                ->whereHas('getTeacherFromBatch', function(Builder $query){
                                    $query->where('batch_id', Auth::user()->batch_id);
                                });
                                // ->where('batch_id', Auth::user()->batch_id);
        if($this->code != NULL){
          $teachers_list = $teachers_list->where('teacher_code',$this->code);
        }
        if($this->shift != NULL){
            $shift_data = $this->shift;
            $teachers_list = $teachers_list->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shift_data){
                                                    $query->where('shift_id', $shift_data)
                                                          ->where('school_id', Auth::user()->school_id)
                                                          ->where('batch_id', Auth::user()->batch_id);
                                                });
        }
        $teachers_list = $teachers_list->with('getTeacherUser')
                                ->with('getUser')
                                ->get();
        return view('backend.report.teacher.profile.profile-excel', [
            'teachers_list' => $teachers_list
        ]);
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
                "Code", 
                "Full Name", 
                'Phone Number',
                "Email",
                'Address',
                'Gender',
                'Marital Status' ,
                'Education',
                'Teacher Designation' ,
                'Designation',
                'Training',
                'Nationality',
                'Religion',
                'Joining Date',
                'Promotion Date',
                'Created By',
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
     // $sheet->mergeCells('A1:O1');
     // $sheet->getStyle('A1:O1')->getFont()->setBold(true);
     // $sheet->mergeCells('A2:O2');
     // $sheet->getStyle('A2:O2')->getFont()->setBold(true);
     // $sheet->getStyle('A3:O3')->getFont()->setBold(true);
    }     

}
