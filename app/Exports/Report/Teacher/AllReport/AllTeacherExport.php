<?php

namespace App\Exports\Report\Teacher\AllReport;

use App\Teacher;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\Report\Teacher\Salary\SalaryExport;
use App\Exports\Report\Teacher\ClassSubject\ClassSubjectsExport;
use App\Exports\Report\Teacher\Attendance\AttendanceReportExport;
use App\Exports\Report\Teacher\Profile\TeacherProfileExport;




class AllTeacherExport implements WithMultipleSheets
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
    	$excTeacherCode = '';
    	$excShiftData = '';
    	$excShift = '';
    	$excDate = $this->date;
    	$sheets[] = new TeacherProfileExport($excTeacherCode,$excShiftData);
    	$sheets[] = new AttendanceReportExport($excShift,$excDate);
    	$sheets[] = new ClassSubjectsExport();
    	$sheets[] = new SalaryExport($excMonth);
    	return $sheets;
    }
}
