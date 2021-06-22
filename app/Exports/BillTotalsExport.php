<?php

namespace App\Exports;

use App\BillTotal;
use App\Shift;
use App\SClass;
use App\Section;
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

class BillTotalsExport implements FromQuery,WithStyles, WithHeadings, WithMapping,ShouldAutoSize,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
     use Exportable;

     protected $excShift,$excClass,$excSection,$excStudent,$excDateFrom,$excDateTo,$excType;

     function __construct($excShift,$excClass,$excSection,$excStudent,$excDateFrom,$excDateTo,$excType) {
     	$this->shift = $excShift;
     	$this->class = $excClass;
     	$this->section = $excSection;
     	$this->student = $excStudent;
     	$this->datefrom = $excDateFrom;
     	$this->dateto = $excDateTo;
     	$this->type = $excType;
     	if($this->shift){
	     	$this->shift_name = Shift::find($this->shift)->value('name');
	    }else{
	     	$this->shift_name = False;
	    }
     	if($this->class){
	     	$this->class_name = SClass::find($this->class)->value('name');
		}else{
	     	$this->class_name = False;
	    }
     	if($this->section){
	     	$this->section_name = Section::find($this->section)->value('name');
     	}else{
	     	$this->section_name = False;
	    }
     }

     public function query()
     {
        $billreport = BillTotal::query()->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
        if(!empty($this->shift)){
        	$shift_data = $this->shift;
            $billreport = $billreport->whereHas('getUserInfo', function (Builder $query) use ($shift_data) {
                              $query->where('shift_id', $shift_data);
                          });
        }
        if(!empty($this->section)){
        	$section_data = $this->section;
            $billreport = $billreport->whereHas('getUserInfo', function (Builder $query) use ($section_data) {
                              $query->where('section_id', $section_data);
                          });
        }
        if(!empty($this->class)){
        	$class_data = $this->class;
            $billreport = $billreport->whereHas('getUserInfo', function (Builder $query) use ($class_data) {
                              $query->where('class_id', $class_data);
                          });
        }
        if(!empty($this->student)){
            $billreport = $billreport->where('user_id',$this->student);
        }

        if($this->datefrom && $this->dateto){
            $billreport = $billreport->whereBetween('bill_date', [$this->dateto, $this->datefrom]);

        }

        if(!empty($this->type)){
        	$billreport = $billreport->where('bill_type',$this->type);
        }
        $total_fee = $billreport->sum('total');
        $net_total_fee = $billreport->sum('nettotal');
        $total_discount = $billreport->sum('discount');
        $total_fine = $billreport->sum('fine');
        
        return $billreport;   
     }

     public function map($billreport): array
     {
     	return [
     		[
     			$billreport->id,
     			$billreport->getStudentinfo->name." ".$billreport->getStudentinfo->middle_name." ".$billreport->getStudentinfo->last_name,
     			$billreport->invoice_id,
     			$billreport->bill_date,
     			$billreport->discount,
     			$billreport->fine,
     			$billreport->total,
     			$billreport->total,
     		]
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
     			"Shift: ".($this->shift_name ? $this->shift_name : 'All').
     			", Class: ".($this->class_name ? $this->class_name : 'All').
     			", Section: ".($this->section_name ? $this->section_name : 'All'),
     		],
     		[
     			"ID", 
     			"Name", 
     			"Invoice Number", 
     			"Date/Time", 
     			"Discount","Fine",
     			"Fee","Total",
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

	public function styles(Worksheet $sheet)
	{
		// $sheet->mergeCells('A1:H1');
		// $sheet->getStyle('A1:H1')->getFont()->setBold(true);
		// $sheet->mergeCells('A2:H2');
		// $sheet->getStyle('A2:H2')->getFont()->setBold(true);
		// $sheet->mergeCells('A3:H3');
		// $sheet->getStyle('A3:H3')->getFont()->setBold(true);

		// $sheet->getStyle('A4:H4')->getFont()->setBold(true);
	}
}
