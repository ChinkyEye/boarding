<?php

namespace App\Exports\Report\Student\AllReport;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Student;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Report\Student\Attendance\AttendanceReportExport;
use App\Exports\Report\Student\Profile\StudentProfileExport;

class AllStudentReport implements WithMultipleSheets
{
    use Exportable;

    protected $excMonth,$excDate;

    function __construct($excMonth,$excDate) {
    	$this->month = $excMonth;
    	$this->date = $excDate;
    }

    public function sheets(): array
    {
    	$sheets = [];
    	$excMonth = $this->month;
    	$excClassData = '';
    	$excShiftData = '';
    	$excSectionData = '';
    	$excShift = '';
    	$excDate = $this->date;
    	$sheets[] = new StudentProfileExport($excShiftData,$excClassData,$excSectionData);
    	$sheets[] = new AttendanceReportExport($excShiftData,$excClassData,$excSectionData,$excDate);
    	return $sheets;
    }
}
