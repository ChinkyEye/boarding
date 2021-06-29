<?php

namespace App\Exports\Report\Teacher\Profile;

use App\Teacher;
use Auth;
use Illuminate\Database\Eloquent\Builder;
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
use Maatwebsite\Excel\Sheet;

class PDFExport implements FromQuery,
                           WithStyles, 
                           WithHeadings, 
                           WithMapping,
                           ShouldAutoSize,
                           WithEvents
{
    use Exportable;

    protected $request;

    function __construct($request) {
        $this->shift = $request->excShift;
        $this->teacher_code = $request->excTeacherCode;
        $this->type = $request->excType;
        // dd($request);
        // dd($this);
    }

    public function query()
    {
        $user = Teacher::query()
                       ->where('school_id', Auth::user()->school_id)
                       ->whereHas('getTeacherFromBatch', function(Builder $query){
                            $query->where('batch_id', Auth::user()->batch_id);
                            });
                       // ->where('batch_id', Auth::user()->batch_id);
        if(!empty($this->shift)){
            $shift = $this->shift;
            $user->whereHas('getShiftTeacherCountList', function (Builder $query) use ($shift){
                                $query->where('shift_id', $shift)
                                      ->where('school_id', Auth::user()->school_id)
                                      ->where('batch_id', Auth::user()->batch_id);
                            });
        }

        if(!empty($this->teacher_code)){
            $user->where('teacher_code', $this->teacher_code);
        }

        $user->with('getTeacherUser')
             ->with('getUser');
        return $user;   
    }

    public function map($user): array
    {
    	// dd($user);
    	return [
    		$user->id,
            $user->teacher_code,
    		$user->getTeacherUser->name." ".$user->getTeacherUser->middle_name." ".$user->getTeacherUser->last_name,
            $user->phone,
    		$user->getTeacherUser->email,
            $user->address,
            $user->gender,
            $user->marital_status,
            $user->qualification,
            $user->t_designation,
            $user->designation,
            $user->training,
            $user->getNationality->n_name,
            $user->religion,
            $user->j_date,
            $user->p_date,
            $user->getUser->name.' '.$user->getUser->middle_name.' '.$user->getUser->last_name,
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
                $event->sheet->getStyle('A3:Q3')->applyFromArray([
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
    	// $sheet->mergeCells('A1:Q1');
        // $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
    	// $sheet->mergeCells('A2:Q2');
        // $sheet->getStyle('A2:Q2')->getFont()->setBold(true);
    }
}
