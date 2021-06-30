<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/base','Controller@getAll')->name('base');
Auth::routes();
// Route::group(['middleware' => ['admin']], function(){
// 	Route::get('/try', 'Backend\NoticeForController@index');
// });
Route::namespace('Backend')->prefix('home')->name('admin.')->middleware(['admin','auth'])->group(function(){
	Route::get('', 'HomeController@index')->name('home');

	Route::resource('setting', 'SettingController');
	Route::resource('profile', 'ProfileController');
	// password
	Route::get('/changepassword','HomeController@showChangePasswordForm')->name('password.index');
	Route::post('change/password/','HomeController@changePassword')->name('change.password');

	//create user
	Route::get('user', 'UserController@index')->name('user.index');
	Route::post('user/store/','UserController@store')->name('user.store');

	Route::resource('main-entry/shift', 'ShiftController');
	Route::get('main-entry/shift/isactive/{id}', 'ShiftController@isactive')->name('shift.active');
	Route::post('main-entry/sort/shift/{id}', 'ShiftController@isSort')->name('shift.sort');
	Route::get('/employee/pdf','ShiftController@createPDF')->name('shift-pdf');
	Route::get('main-entry/shifts/shift-excel', 'ShiftController@shiftExport')->name('shift-export');

	Route::resource('main-entry/period', 'PeriodController');
	Route::get('main-entry/period/isactive/{id}', 'PeriodController@isactive')->name('period.active');
	Route::post('main-entry/sort/period/{id}', 'PeriodController@isSort')->name('period.sort');

	Route::resource('main-entry/batch','BatchController');
	Route::get('main-entry/batch/active/{id}','BatchController@isActive')->name('batch.active');
	Route::resource('main-entry/nationality', 'NationalityController');
	Route::get('main-entry/nationality/active/{id}', 'NationalityController@isActive')->name('nationality.active');
	Route::post('main-entry/nationality/sort/{id}', 'NationalityController@isSort')->name('nationality.sort');
	Route::get('main-entry/nationality/export/format', 'NationalityController@export')->name('nationality.export');
	Route::resource('main-entry/parent', 'ParentController');

	Route::resource('main-entry/section', 'SectionController');
	Route::get('main-entry/section/{id}/isActive', 'SectionController@isActive')->name('section.active');
	Route::post('main-entry/sort/section/{id}', 'SectionController@isSort')->name('section.sort');

	Route::resource('main-entry/class', 'SClassController');
	Route::get('main-entry/class/isActive/{id}', 'SClassController@isActive')->name('class.active');
	Route::post('main-entry/class/sort/{id}', 'SClassController@isSort')->name('class.sort');

	Route::get('main-entry/c_has_shift/{slug}', 'ClassHasShiftController@main')->name('class_has_shift');
	Route::resource('main-entry/c_has_shift', 'ClassHasShiftController');
	Route::get('main-entry/c_has_shift/isactive/{id}', 'ClassHasShiftController@isactive')->name('c_has_shift.active');

	Route::get('main-entry/c_has_section/{slug}', 'ClassHasSectionController@main')->name('class_has_section');
	Route::resource('main-entry/c_has_section', 'ClassHasSectionController');
	Route::get('main-entry/c_has_section/isactive/{id}', 'ClassHasSectionController@isactive')->name('c_has_section.active');
	Route::post('main-entry/c_has_section/getShiftList', 'ClassHasSectionController@getShiftList')->name('getShiftList');


	Route::get('main-entry/class/subject/{slug}', 'SubjectController@main')->name('subject');
	Route::resource('main-entry/class/has/subject', 'SubjectController');
	Route::get('main-entry/class/subject/isactive/{id}', 'SubjectController@isactive')->name('subject.active');
	Route::post('main-entry/class/sort/subject/{value}', 'SubjectController@isSort')->name('subject.sort');
	

	Route::resource('primary-entry/teacher', 'TeacherController');
	Route::get('primary-entry/teacher/{id}/isActive', 'TeacherController@isActive')->name('teacher.active');
	Route::post('primary-entry/sort/teacher/{value}', 'TeacherController@isSort')->name('teacher.sort');
	Route::post('primary-entry/teacher/getAllTeacher', 'TeacherController@getAllTeacher')->name('getAllTeacher');
	Route::get('/primary-entry/teacher/export/excel', 'TeacherController@export')->name('teacher.export');
	Route::get('primary-entry/teacher/reset/password/{id}', 'TeacherController@resetPassword')->name('teacher.resetPassword');
	Route::get('primary-entry/teacher/card/print/{id}', 'TeacherController@cardPrint')->name('teacher.cardPrint');

	
	Route::resource('primary-entry/student', 'StudentController');
	Route::post('/primary-entry/student/checkemail', 'StudentController@checkemail')->name('student_email.check');
	Route::post('/primary-entry/student/checkrollno', 'StudentController@checkrollno')->name('student_rollno.check');
	Route::get('primary-entry/student/isactive/{id}', 'StudentController@isactive')->name('student.active');
	Route::post('primary-entry/student/sort/{id}', 'StudentController@isSort')->name('student.sort');
	Route::post('primary-entry/student/getAllStudent', 'StudentController@getAllStudent')->name('getAllStudent');
	Route::get('primary-entry/student/reset/password/{id}', 'StudentController@resetPassword')->name('student.resetPassword');
	Route::get('primary-entry/student/certificate/{id}', 'StudentController@certificate')->name('student.certificate');
	Route::get('upgrage','StudentController@upgrade')->name('student.upgrade');

	// search input all
	Route::post('primary-entry/student/getClassList', 'StudentController@getClassList')->name('getClassList');
	Route::post('primary-entry/student/getSectionList', 'StudentController@getSectionList')->name('getSectionList');

	Route::post('primary-entry/student/getShiftTeacherList', 'StudentController@getShiftTeacherList')->name('getShiftTeacherList');
	Route::post('primary-entry/student/getSectionSubjectList', 'StudentController@getSectionSubjectList')->name('getSectionSubjectList');
	Route::post('primary-entry/student/getTeacherClassSalaryList', 'StudentController@getTeacherClassSalaryList')->name('getTeacherClassSalaryList');
	
	Route::post('primary-entry/student/getAllStudentMark', 'StudentHasMarkController@getAllStudentMark')->name('getAllStudentMark');
	Route::post('primary-entry/student/getExamList', 'StudentHasMarkController@getExamList')->name('getExamList');
	Route::post('primary-entry/student/getExamShiftList', 'StudentHasMarkController@getExamShiftList')->name('getExamShiftList');
	Route::post('primary-entry/student/getExamClassList', 'StudentHasMarkController@getExamClassList')->name('getExamClassList');
	Route::post('primary-entry/student/getExamSectionList', 'StudentHasMarkController@getExamSectionList')->name('getExamSectionList');
	// Route::get('primary-entry/student/getAllStudentClassMark', 'StudentHasMarkController@getAllStudentClassMark')->name('getAllStudentClassMark');

	Route::get('primary-entry/student/export/excel', 'StudentController@export')->name('student.export');
	Route::get('primary-entry/student/idcard/export', 'StudentController@idcard')->name('student.idcard');
	Route::get('primary-entry/student/allcertificate/export', 'StudentController@getAllCertificate')->name('student.allcertificate');
	Route::get('primary-entry/student/detail/{id}', 'StudentController@detailPrint')->name('student.detail.print');
	Route::get('primary-entry/student/print/{id}', 'StudentController@print')->name('student.print');
	Route::get('primary-entry/student/admit/{id}', 'StudentController@admit')->name('student.admit');
	Route::post('primary-entry/student/getStudentCount', 'StudentController@getStudentCount')->name('student.getStudentCount');
	Route::post('primary-entry/student/count/print/idcard', 'StudentController@count')->name('student.count');


	Route::get('primary-entry/teacher/period/{slug}', 'TeacherHasPeriodController@index')->name('teacherhasperiod');
	Route::resource('primary-entry/teacherhasperiod', 'TeacherHasPeriodController');
	Route::get('primary-entry/teacher/bank/{slug}', 'StaffHasBankController@index')->name('staffhasbank');
	Route::resource('primary-entry/staffhasbank', 'StaffHasBankController');

	Route::get('primary-entry/teacher/period/isactive/{id}', 'TeacherHasPeriodController@isactive')->name('teacherhasperiod.active');
	Route::post('primary-entry/teacher/sort/period/{value}', 'TeacherHasPeriodController@isSort')->name('teacherhasperiod.sort');
	Route::post('primary-entry/teacher/getTeacherRoutineClassList', 'TeacherHasPeriodController@getTeacherRoutineClassList')->name('getTeacherRoutineClassList');

	// attendence
	Route::resource('attendance/teacher-attendance', 'TeacherHasAttendanceController');
	Route::post('attendance/teacher-attendance/getAllTeacherAttendance', 'TeacherHasAttendanceController@getAllTeacherAttendance')->name('getAllTeacherAttendance');
	Route::post('attendance/teacher-attendance/getDateList', 'TeacherHasAttendanceController@getDateList')->name('getDateList');
	Route::post('attendance/teacher-attendance/getTeacherList', 'TeacherHasAttendanceController@getTeacherList')->name('getTeacherList');
	Route::get('attendance/teacher-attendance/export/excel', 'TeacherHasAttendanceController@export')->name('teacher-attendance.export');

	Route::get('attendance/student-attendance', 'StudentHasAttendanceController@index')->name('student-attendance.index');
	Route::post('attendance/teacher-student-attendance', 'StudentHasAttendanceController@store')->name('teacher-student-attendance.store');
	// ajax student search
	Route::post('attendance/student-attendance/getStudentAttendenceList', 'StudentHasAttendanceController@getStudentAttendenceList')->name('getStudentAttendenceList');
	//export excel
	Route::get('attendance/student-attendance/export/excel', 'StudentHasAttendanceController@export')->name('student-attendance.export');

	//teacher attendance report
	Route::get('attendance/report/teacher-attendance', 'Report\TeacherAttendanceReportController@index')->name('teacherattendance-report.index');
	Route::get('attendance/report/teacher-attendance/getReportTeacherAttendance', 'Report\TeacherAttendanceReportController@getReportTeacherAttendance')->name('getReportTeacherAttendance');
	// teacher profile report
	Route::get('report/teacher/profile', 'Report\TeacherReportController@profile')->name('report.teacher.profile');
	Route::get('report/teacher/profile/search', 'Report\TeacherReportController@profileSearch')->name('report.teacher.profile.search');
	//teacher attendance report
	Route::get('report/teacher/attendance', 'Report\TeacherReportController@attendance')->name('report.teacher.attendance');
	Route::get('report/teacher/attendance/search', 'Report\TeacherReportController@attendanceSearch')->name('report.teacher.attendance.search');
	Route::get('report/teacher/attendance/view/detail/{id}', 'Report\TeacherReportController@attendanceDetail')->name('report.teacher.attendance.detail');
	Route::get('report/teacher/attendance/view/month/detail', 'Report\TeacherReportController@attendanceMonthDetail')->name('report.teacher.attendance.month.search');
	//teacher salary report

	Route::get('report/teacher/salary', 'Report\TeacherReportController@salary')->name('report.teacher.salary');
	Route::get('report/teacher/salary/search', 'Report\TeacherReportController@salarySearch')->name('report.teacher.salary.search');
	//teacher subjectclasssreport
	Route::get('report/teacher/subject/class/list', 'Report\TeacherReportController@SubjectClassList')->name('report.teacher.SubjectClassList');

	//report export in excel
	Route::get('report/teacher/profile/export/pdf', 'Report\TeacherReportController@exportProfile')->name('report.teacher.profile.export');
	Route::get('report/teacher/profile/export/excel', 'Report\TeacherReportController@exportProfileExcel')->name('report.profile.export.excel');
	//teacher profile export
	Route::get('report/teacher/profile/export/excel/format', 'Report\TeacherReportController@exportTeacherProfile')->name('report.teacher.profile.export.excel');
	//teacher subjectclasslist export

	Route::get('report/teacher/subjectclasslist/export/excel', 'Report\TeacherReportController@exportTeacherSubjectClassList')->name('report.subjectclasslist.export.excel');
	// teacher salary report excel

	Route::get('report/teacher/salarylist/export/excel', 'Report\TeacherReportController@exportTeacherSalary')->name('report.teacher.salarylist.export');
	//teacher attendance report excel
	Route::get('report/teacher/attendancelist/export/excel', 'Report\TeacherReportController@exportTeacherAttendance')->name('report.teacher.attendancelist.export');
	Route::get('report/teacher/attendance/monthdetail/export/excel', 'Report\TeacherReportController@exportTeacherDetailMonthAttendance')->name('report.teacher.attendance.detail.export.excel');
	//all teacher report
	Route::get('report/teacher/report/all/export', 'Report\TeacherReportController@exportAllTeacherReport')->name('report.export.allteacherreport');

	//student  report
	Route::get('report/student/profile', 'Report\StudentReportController@profile')->name('report.student.profile');
	Route::get('report/student/profile/search', 'Report\StudentReportController@profileSearch')->name('report.student.profile.search');
	Route::get('report/student/attendance', 'Report\StudentReportController@attendance')->name('report.student.attendance');
	Route::get('report/student/attendance/search', 'Report\StudentReportController@attendanceSearch')->name('report.student.attendance.search');
	Route::get('report/student/attendance/view/detail/{id}', 'Report\StudentReportController@attendanceDetail')->name('report.student.attendance.detail');
	Route::get('report/student/attendance/view/month/detail', 'Report\StudentReportController@attendanceMonthDetail')->name('report.student.attendance.month.search');

	// student profile report excel 
	Route::get('report/student/profile/export/pdf', 'Report\StudentReportController@exportProfile')->name('report.student.profile.export');
	//student attendance report export
	Route::get('report/student/attendancelist/export/excel', 'Report\StudentReportController@exportStudentAttendance')->name('report.student.attendancelist.export');
	Route::get('report/student/attendance/monthdetail/export/excel', 'Report\StudentReportController@exportStudentDetailMonthAttendance')->name('report.student.attendance.detail.export.excel');
	//all student report export in excel
	Route::get('report/student/report/all/export', 'Report\StudentReportController@exportAllStudentReport')->name('report.export.allstudentreport');

	


	//studentattendance report
	Route::get('attendance/report/student-attendance', 'Report\StudentAttendanceReportController@index')->name('studentattendance-report.index');
	Route::get('attendance/report/student-attendance/getReportStudentAttendance', 'Report\StudentAttendanceReportController@getReportStudentAttendance')->name('getReportStudentAttendance');


	Route::resource('daily-record/routine', 'RoutineController');
	Route::post('daily-record/routine/getRoutineRecord', 'RoutineController@getRoutineRecord')->name('getRoutineRecord');
	Route::get('daily-record/routine/isactive/{id}', 'RoutineController@isactive')->name('routine.active');
	Route::post('daily-record/routine/sort/{id}', 'RoutineController@isSort')->name('routine.sort');

	// to get teacher, class, section list
	Route::post('primary-entry/student/getShiftRTeacherList', 'RoutineController@getShiftRTeacherList')->name('getShiftRTeacherList');
	Route::post('primary-entry/student/getShiftRClassList', 'RoutineController@getShiftRClassList')->name('getShiftRClassList');
	Route::post('primary-entry/student/getShiftRSectionList', 'RoutineController@getShiftRSectionList')->name('getShiftRSectionList');
	Route::post('primary-entry/student/getShiftRSubjectList', 'RoutineController@getShiftRSubjectList')->name('getShiftRSubjectList');
	// get teacher routine list
	Route::post('primary-entry/student/getTeacherRoutineList', 'RoutineController@getTeacherRoutineList')->name('getTeacherRoutineList');
	
	// homework
	Route::resource('daily-record/homework','HomeworkController');
	Route::get('daily-record/homework/isactive/{id}', 'HomeworkController@isactive')->name('homework.active');
	Route::post('daily-record/homework/sort/{id}', 'HomeworkController@isSort')->name('homework.sort');
	// ajax
	Route::post('daily-record/homework/getHomeworkRecord', 'HomeworkController@getHomeworkRecord')->name('getHomeworkRecord');

	//exam
	Route::resource('exam-section/exam', 'ExamController');

	Route::resource('exam-section/grade', 'GradeController');
	Route::resource('exam-section/observation', 'ObservationController');
	Route::get('exam-section/grade/active/{id}', 'GradeController@isActive')->name('grade.active');

	Route::get('exam-section/examhasclass/{slug}', 'ExamHasClassController@main')->name('examhasclass');
	Route::resource('exam-section/examhasclass', 'ExamHasClassController');
	Route::get('exam-section/examhasclass/isactive/{id}', 'ExamHasClassController@isactive')->name('examhasclass.active');
	Route::get('exam-section/examhasclass/student/admitcard/{id}', 'ExamHasClassController@admitcard')->name('examhasclass.export');


	Route::get('exam-section/classhasmark/{exam}/{slug}', 'ClassHasMarkController@main')->name('classhasmark');
	Route::resource('exam-section/classhasmark', 'ClassHasMarkController');
	Route::get('exam-section/classhasmark/isactive/{id}', 'ClassHasMarkController@isactive')->name('classhasmark.active');

	// ledger
	Route::get('exam-section/student/mark/ledger', 'LedgerController@index')->name('studenthasmark.ledger');
	Route::get('exam-section/student/mark/ledger/view', 'LedgerController@create')->name('studenthasmark.ledgermark');
	Route::post('exam-section/studenthasmark/storemark', 'LedgerController@store')->name('studenthasmark.storemark');
	Route::get('exam-section/student/ledger/mark/view', 'LedgerController@ledgerView')->name('studenthasmark.ledger.view');
	Route::get('exam-section/student/ledger/mark/view/show', 'LedgerController@show')->name('studenthasmark.ledger.show');

	// marks
	Route::get('exam-section/studenthasmark/{slug}/{exam}', 'StudentHasMarkController@main')->name('studenthasmark');
	Route::post('exam-section/studenthasmark/publish/mark/all', 'StudentHasMarkController@isPublish')->name('studentmarkpublish.active');
	Route::post('exam-section/studenthasmark/unpublish/mark/all', 'StudentHasMarkController@isunPublish')->name('studentmarkunpublish.active');
	Route::get('exam-section/studenthasmark/individual/publishunpublish/{invoiceid}', 'StudentHasMarkController@isSinglePublish')->name('singlestudentmarkpublish.active');
	Route::get('exam-section/studenthasmark/getMarkPrint/{slug}/{exam}', 'LedgerController@getMarkPrint')->name('studenthasmark.getMarkPrint');
	Route::get('exam-section/studenthasmark/getGradePrint/{slug}/{exam}', 'LedgerController@getGradePrint')->name('studenthasmark.getGradePrint');
	Route::get('exam-section/studenthasmark/getBothPrint/{slug}/{exam}', 'LedgerController@getBothPrint')->name('studenthasmark.getBothPrint');
	Route::get('exam-section/studenthasmark/getmark', 'StudentHasMarkController@getExamList')->name('studenthasmark.getmark');
	Route::resource('exam-section/studenthasmark', 'StudentHasMarkController');
	Route::post('exam-section/studenthasmark/getStudentExamGrade', 'StudentHasMarkController@getStudentExamGrade')->name('getStudentExamGrade');
	Route::get('exam-section/observation/student/{user_id}/{classexam_id}/mark', 'StudentHasObservationController@mark')->name('observation.mark');
	Route::resource('exam-section/student/observationmark', 'StudentHasObservationController');
	Route::get('exam-section/studenthasmarksheet/{slug}/{exam}', 'StudentHasMarkController@marksheet')->name('studenthasmarksheet');


	//library
	Route::post('library/book/getAllBook', 'BookController@getAllBook')->name('getAllBook');
	Route::post('library/book/getSubjectList', 'BookController@getSubjectList')->name('getSubjectList');
	Route::resource('library/book', 'BookController');

	Route::resource('library/issuebook', 'IssueBookController');
	
	Route::post('library/issuebook/list/getBookList', 'IssueBookController@getBookList')->name('getBookList');
	Route::post('library/issuebook/list/getStudentList', 'IssueBookController@getStudentList')->name('getStudentList');
	Route::post('library/issuebook/list/getStudentIssueList', 'IssueBookController@getStudentIssueList')->name('getStudentIssueList');
	Route::post('library/issuebook/list/getIssuebookRecord', 'IssueBookController@getIssuebookRecord')->name('getIssuebookRecord');

	Route::get('library/issuebook/getReturnDate/{data_id}', 'IssueBookController@getReturnDate')->name('getReturnDate');
	Route::post('library/issuebook/return-date/save', 'IssueBookController@getReturnDateSave')->name('issuebook.getReturnDateSave');

	// notice
	Route::resource('notice', 'NoticeController');
	Route::post('notice/getNoticeList', 'NoticeController@getNoticeList')->name('getNoticeList'); // notice ajax
	Route::post('notice/getNoticeShift', 'NoticeController@getNoticeShift')->name('getNoticeShift');
	Route::post('notice/getSaveButtom', 'NoticeController@getSaveButtom')->name('getSaveButtom');
	Route::post('notice/getNoticeClassList', 'NoticeController@getNoticeClassList')->name('getNoticeClassList');
	Route::post('notice/getNoticeSectionList', 'NoticeController@getNoticeSectionList')->name('getNoticeSectionList');

	Route::get('notice/add/{slug}', 'NoticeForController@index')->name('addnotice');
	Route::post('notice/add', 'NoticeForController@store')->name('addnotice.store');



	Route::post('notice/getEventList', 'EventController@getEventList')->name('getEventList');
	Route::resource('event', 'EventController');

	Route::resource('account-section/topic', 'TopicController');
	Route::post('account-section/topic/getAllAccountTopic', 'TopicController@getAllAccountTopic')->name('getAllAccountTopic');
	Route::get('account-section/topic/isactive/{id}', 'TopicController@isactive')->name('topic.active');

	Route::resource('account-section/bill', 'BillController');
	Route::post('account-section/bill/getAllAccountBill', 'BillController@getAllAccountBill')->name('getAllAccountBill');
	Route::get('account-section/bill/isactive/{id}', 'BillController@isactive')->name('bill.active');
	Route::post('primary-entry/student/getStudentBillList', 'BillController@getStudentBillList')->name('getStudentBillList');
	Route::get('account-section/bill/studentlist/{slug}', 'BillController@main')->name('bill.studentlist');
	Route::get('account-section/bill/studentlist/{bill_id}/print', 'BillController@print')->name('bill.print');
	// fee
	Route::resource('account-section/fee', 'FeeController');
	Route::post('primary-entry/student/getStudentFeeList', 'FeeController@getStudentFeeList')->name('getStudentFeeList');
	Route::get('account-section/fee/studentfee/{slug}', 'FeeController@main')->name('fee.student');
	Route::get('account-section/fee/viewstudentfee/{slug}', 'FeeController@getFee')->name('fee.viewstudentfee');
	Route::get('account-section/fee/isactive/{id}', 'FeeController@isactive')->name('fee.active');
	Route::post('account-section/fee/viewstudent/bill/total', 'BillController@getBillFeeList')->name('getBillFeeList');
	Route::post('account-section/fee/viewstudent/bill/calculate', 'BillController@getBillCalculateList')->name('getBillCalculateList');
	Route::post('account-section/fee/viewstudent/bill/calculate/remaining', 'FeeController@getBillRemainedList')->name('getBillRemainedList');
	Route::post('account-section/fee/viewstudent/bill/check/amount', 'FeeController@getAmountCheckList')->name('getAmountCheckList');
	// fee report
	Route::get('account-section/report/fee-report', 'Report\FeeReportController@index')->name('fee-report.index');
	Route::get('account-section/report/fee-report/getReportFee', 'Report\FeeReportController@getReportFee')->name('getReportFee');
	Route::get('account-section/report/fee-report/export/excel', 'Report\FeeReportController@export')->name('fee-report.export');

	

	Route::get('account-section/salary/ledger', 'TeacherIncomeController@ledger')->name('ledger');
	Route::get('account-section/salary/laganikosh/{id}', 'TeacherIncomeController@NLKfund')->name('laganikosh');
	Route::get('account-section/salary/journal/{id}', 'TeacherIncomeController@journal')->name('journal');
	Route::get('account-section/salary/pfledger/{id}', 'TeacherIncomeController@pfLedger')->name('pfledger');

	Route::get('account-section/salary/talabmaagfarum/{id}', 'TeacherIncomeController@farum')->name('talabmaagfarum');
	Route::get('account-section/salary/gosawara/voucher/{id}', 'TeacherIncomeController@gosawaraVoucher')->name('gosawaravoucher');
	Route::get('account-section/salary/gosawara/voucher/kharcha/{id}', 'TeacherIncomeController@gosawaraVoucherKharcha')->name('gosawaravoucherkharcha');

	Route::get('account-section/salary/citizen_ledger/{id}', 'TeacherIncomeController@citizenLedger')->name('citizen_ledger');
	Route::get('account-section/salary/socialtax_ledger/{id}', 'TeacherIncomeController@socialtaxLedger')->name('socialtax_ledger');

	Route::post('account-section/salary/getLedgerInfoList', 'TeacherIncomeController@getLedgerInfoList')->name('getLedgerInfoList');
	Route::resource('account-section/salary', 'TeacherIncomeController');
	Route::post('account-section/salary/getTeacherInfoList', 'TeacherIncomeController@getTeacherInfoList')->name('getTeacherInfoList');
	Route::post('account-section/salary/getTeacherDetailList', 'TeacherIncomeController@getTeacherDetailList')->name('getTeacherDetailList');

	//calender

	Route::resource('calender-section/calender', 'CalenderController');
	Route::get('/getEvents', 'CalenderController@getEvents')->name('calender.getEvents');
});

Route::namespace('Student')->prefix('student')->name('student.')->middleware(['student'])->group(function(){
	Route::get('', 'HomeController@index')->name('home');
	Route::get('dashboard', 'HomeController@loadProfile')->name('student-detail');
	Route::get('attendance', 'StudentHasAttendanceController@index')->name('student-attendance');
	Route::resource('homework', 'HomeworkController',['except' => ['create', 'edit', 'update', 'destroy']]);
	Route::get('book', 'BookController@index')->name('student-book');
	Route::resource('exam', 'ExamController',['except' => ['create', 'edit', 'update', 'destroy']]);
	// Route::get('exam', 'ExamController@index')->name('index');
	Route::resource('account', 'AccountController',['except' => ['create', 'edit', 'update', 'destroy']]);
	Route::resource('notice', 'NoticeController',['except' => ['create', 'edit', 'update', 'destroy']]);
	Route::get('event', 'EventController@index')->name('student-event');
	Route::post('change-password','HomeController@changePassword')->name('changepassword');
});

Route::namespace('Teacher')->prefix('teacher')->name('teacher.')->middleware(['teacher'])->group(function(){
	// dashboard with profile info
	Route::get('', 'HomeController@index')->name('home');
	Route::post('about-us/getAttendanceList', 'HomeController@getAttendanceList')->name('getAttendanceList');
	
	//change password
	Route::get('/changepassword','HomeController@showChangePasswordForm')->name('password.index');
	Route::post('change/password/','HomeController@changePassword')->name('change.password');

	// event
	Route::get('event', 'EventController@index')->name('teacher-event');

	// notice
	Route::resource('notice', 'NoticeController');
	Route::post('notice/getNoticeList', 'NoticeController@getNoticeList')->name('getNoticeList');

	//studenthasmark filtering
	Route::post('primary-entry/student/getExamList', 'StudentHasMarkController@getExamList')->name('getExamList');
	Route::post('primary-entry/student/getExamSectionList', 'StudentHasMarkController@getExamSectionList')->name('getExamSectionList');
	Route::post('primary-entry/student/getExamShiftList', 'StudentHasMarkController@getExamShiftList')->name('getExamShiftList');
	Route::post('primary-entry/student/getExamClassList', 'StudentHasMarkController@getExamClassList')->name('getExamClassList');

	//mark section
	Route::get('exam-section/studenthasmark/{slug}/{exam}', 'StudentHasMarkController@main')->name('studenthasmark');
	Route::post('exam-section/studenthasmark/getStudentExamGrade', 'StudentHasMarkController@getStudentExamGrade')->name('getStudentExamGrade');
	Route::resource('exam-section/studenthasmark', 'StudentHasMarkController');

	//ledger
	Route::get('exam-section/student/mark/ledger', 'LedgerController@index')->name('studenthasmark.ledger');
	Route::get('exam-section/student/mark/ledger/view', 'LedgerController@create')->name('studenthasmark.ledgermark');
	Route::post('exam-section/studenthasmark/storemark', 'LedgerController@store')->name('studenthasmark.storemark');
	Route::post('exam-section/teacher/count/print', 'LedgerController@countMark')->name('mark.count');
	Route::get('exam-section/studenthasmark/getMarkPrint/{slug}/{exam}', 'LedgerController@getMarkPrint')->name('studenthasmark.getMarkPrint');
	Route::get('exam-section/studenthasmark/getGradePrint/{slug}/{exam}', 'LedgerController@getGradePrint')->name('studenthasmark.getGradePrint');
	Route::get('exam-section/studenthasmark/getBothPrint/{slug}/{exam}', 'LedgerController@getBothPrint')->name('studenthasmark.getBothPrint');
	Route::get('exam-section/student/mark/view/ledger/mark', 'LedgerController@ledger')->name('studenthasmark.ledger.view');
	Route::get('exam-section/studenthasmark/view/ledger/mark/show', 'LedgerController@show')->name('studenthasmark.ledger.show');

	//studentattendance
	Route::resource('student-attendance', 'StudentHasAttendanceController', ['except' => ['create', 'update', 'destroy']]);
	// student ajax
	Route::post('attendance/student-attendance/getStudentAttendance', 'StudentHasAttendanceController@getStudentAttendance')->name('getStudentAttendance');

	// search
	Route::post('primary-entry/student/tgetClassList', 'HomeController@getClassList')->name('tgetClassList');
	Route::post('primary-entry/student/tgetSectionList', 'HomeController@getSectionList')->name('tgetSectionList');
	Route::post('primary-entry/student/tgetSubjectList', 'HomeController@getSubjectList')->name('tgetSubjectList');

	// search append check remaining ************* //
	Route::post('primary-entry/student/getClassList', 'StudentController@getClassList')->name('getClassList');
	Route::post('primary-entry/student/getSectionList', 'StudentController@getSectionList')->name('getSectionList');
	// ****************************

	// attendance *************** //
	Route::get('attendance/teacher-attendance', 'TeacherHasAttendanceController@index')->name('teacher-attendance');
	Route::post('attendance/teachesr-attendance/getTeacherList', 'TeacherHasAttendanceController@getTeacherList')->name('getTeacherList');
	// ************************* //
	
	//routine
	Route::post('daily-record/routine/getRoutineRecord', 'RoutineController@getRoutineRecord')->name('getRoutineRecord');
	Route::resource('daily-record/routine', 'RoutineController');

	//homework section
	Route::get('daily-record/homework/isactive/{id}', 'HomeworkController@isactive')->name('homework.active');
	Route::post('daily-record/homework/sort/{id}', 'HomeworkController@isSort')->name('homework.sort');
	// Route::post('daily-record/homework/getAllHomework', 'HomeworkController@getAllHomework')->name('getAllHomework');
	Route::post('daily-record/homework/getHomeworkRecord', 'HomeworkController@getHomeworkRecord')->name('getHomeworkRecord');
	Route::resource('daily-record/homework','HomeworkController');

	//remainder section
	Route::post('remainder/getRemainderList', 'RemainderController@getRemainderList')->name('getRemainderList');
	Route::resource('remainder','RemainderController');
});

Route::namespace('Library')->prefix('library')->name('library.')->middleware(['library'])->group(function(){
	Route::get('', 'HomeController@index')->name('home');
	Route::resource('book', 'BookController');
	Route::post('book/getAllBook', 'BookController@getAllBook')->name('getAllBook');
	Route::post('book/getSubjectList', 'BookController@getSubjectList')->name('getSubjectList');

	Route::post('student/getClassList', 'StudentController@getClassList')->name('getClassList');
	Route::post('student/getSectionList', 'StudentController@getSectionList')->name('getSectionList');

	Route::resource('issuebook', 'IssueBookController');
	
	Route::post('issuebook/list/getBookList', 'IssueBookController@getBookList')->name('getBookList');
	Route::post('issuebook/list/getStudentList', 'IssueBookController@getStudentList')->name('getStudentList');
	Route::post('issuebook/list/getStudentIssueList', 'IssueBookController@getStudentIssueList')->name('getStudentIssueList');
	Route::post('issuebook/list/getIssuebookRecord', 'IssueBookController@getIssuebookRecord')->name('getIssuebookRecord');

	Route::get('issuebook/getReturnDate/{data_id}', 'IssueBookController@getReturnDate')->name('getReturnDate');
	Route::post('issuebook/return-date/save', 'IssueBookController@getReturnDateSave')->name('issuebook.getReturnDateSave');
	
	// Route::post('change-password','HomeController@changePassword')->name('changepassword');
});

Route::namespace('Accountant')->prefix('account')->name('account.')->middleware(['account'])->group(function(){
	Route::get('', 'HomeController@index')->name('home');
	Route::resource('topic', 'TopicController');
	Route::post('topic/getAllAccountTopic', 'TopicController@getAllAccountTopic')->name('getAllAccountTopic');
	Route::get('topic/isactive/{id}', 'TopicController@isactive')->name('topic.active');
	Route::resource('bill', 'BillController');

	// search append check remaining ************* //
	Route::post('primary-entry/student/getClassList', 'StudentController@getClassList')->name('getClassList');
	Route::post('primary-entry/student/getSectionList', 'StudentController@getSectionList')->name('getSectionList');
	// ****************************
	// Bill
	Route::post('bill/getAllAccountBill', 'BillController@getAllAccountBill')->name('getAllAccountBill');
	Route::post('primary-entry/student/getStudentBillList', 'BillController@getStudentBillList')->name('getStudentBillList');
	Route::get('bill/studentlist/{slug}', 'BillController@main')->name('bill.studentlist');
	Route::post('fee/viewstudent/bill/total', 'BillController@getBillFeeList')->name('getBillFeeList');
	Route::post('fee/viewstudent/bill/calculate', 'BillController@getBillCalculateList')->name('getBillCalculateList');
	Route::get('bill/studentlist/{bill_id}/print', 'BillController@print')->name('bill.print');

	// fee
	Route::resource('fee', 'FeeController');
	Route::post('primary-entry/student/getStudentFeeList', 'FeeController@getStudentFeeList')->name('getStudentFeeList');
	Route::get('fee/studentfee/{slug}', 'FeeController@main')->name('fee.student');
	Route::get('fee/isactive/{id}', 'FeeController@isactive')->name('fee.active');
	Route::get('fee/viewstudentfee/{slug}', 'FeeController@getFee')->name('fee.viewstudentfee');
	Route::resource('attendance/teacher-attendance', 'TeacherHasAttendanceController');


	//ledger
	Route::get('salary/ledger', 'TeacherIncomeController@ledger')->name('ledger');
	Route::post('salary/getLedgerInfoList', 'TeacherIncomeController@getLedgerInfoList')->name('getLedgerInfoList');
	Route::get('salary/laganikosh/{id}', 'TeacherIncomeController@NLKfund')->name('laganikosh');
	Route::get('salary/journal/{id}', 'TeacherIncomeController@journal')->name('journal');
	Route::get('salary/pfledger/{id}', 'TeacherIncomeController@pfLedger')->name('pfledger');

	Route::get('salary/talabmaagfarum/{id}', 'TeacherIncomeController@farum')->name('talabmaagfarum');
	Route::get('salary/gosawara/voucher/{id}', 'TeacherIncomeController@gosawaraVoucher')->name('gosawaravoucher');
	Route::get('salary/gosawara/voucher/kharcha/{id}', 'TeacherIncomeController@gosawaraVoucherKharcha')->name('gosawaravoucherkharcha');


	// income
	Route::resource('salary', 'TeacherIncomeController');
	Route::post('salary/getTeacherInfoList', 'TeacherIncomeController@getTeacherInfoList')->name('getTeacherInfoList');
	Route::post('salary/getTeacherDetailList', 'TeacherIncomeController@getTeacherDetailList')->name('getTeacherDetailList');




});

Route::namespace('Main')->prefix('main')->name('main.')->middleware(['main'])->group(function(){
	Route::get('', 'HomeController@index')->name('home');
	//password
	Route::resource('batch','BatchController');
	Route::get('batch/active/{id}','BatchController@isActive')->name('batch.active');
	Route::get('/changepassword','HomeController@showChangePasswordForm')->name('password.index');
	Route::post('change/password/','HomeController@changePassword')->name('change.password');

	Route::get('school/isactive/{id}', 'SchoolController@isactive')->name('school.isactive');
	Route::get('school/show/{slug}', 'SchoolController@show')->name('school.show');
	Route::resource('school','SchoolController');

	Route::get('school/createadmin/{slug}', 'AdminController@main')->name('create-admin');
	Route::get('school/createadmin/reset/password/{id}', 'AdminController@reset')->name('admin.reset');

	Route::resource('admin','AdminController');

	// school dashboard
	Route::get('school/info/{slug}', 'School\SchoolInfoController@index')->name('school.info.index');

	// ajax search duita route xa ata
	// Route::post('school/info/{slug}/getClassList', 'School\SchoolInfoController@getClassList')->name('getClassList');
	// Route::post('school/info/{slug}/getSectionList', 'School\SchoolInfoController@getSectionList')->name('getSectionList');
	
	// teacher
	Route::resource('school/info/{slug}/teacher', 'School\TeacherController');
	Route::get('school/info/{slug}/teacher/{id}/isActive', 'School\TeacherController@isActive')->name('teacher.active');
	Route::post('school/info/{slug}/sort/teacher/{value}', 'School\TeacherController@isSort')->name('teacher.sort');
	Route::post('school/info/{slug}/teacher/getAllTeacher', 'School\TeacherController@getAllTeacher')->name('getAllTeacher');
	Route::get('/school/info/{slug}/teacher/export/{id}', 'School\TeacherController@export')->name('teacher.export');

	//teacher report controller profile
	Route::get('school/info/{slug}/report/allteacher-report/profile', 'School\TeacherReportController@profileList')->name('school.teacher.report.profile');
	Route::get('school/info/{slug}/report/allteacher-report/profile/search', 'School\TeacherReportController@profileSearchList')->name('school.teacher.report.profile.search');

	//all teacher report for classsubject
	Route::get('school/info/{slug}/report/allteacher-report/subject/classlist', 'School\TeacherReportController@subjectClassList')->name('school.teacher.report.subjectclass');

	//all teacher report for attendance
	Route::get('school/info/{slug}/report/allteacher-report/attendance/list', 'School\TeacherReportController@attendanceList')->name('school.teacher.report.attendance');
	Route::get('school/info/{slug}/report/allteacher-report/attendance/search', 'School\TeacherReportController@attendanceSearchList')->name('school.teacher.report.attendance.search');
	Route::get('school/info/{slug}/report/allteacher-report/attendance/detail/{user_id}', 'School\TeacherReportController@attendanceDetailList')->name('school.teacher.report.attendance.detail');
	Route::get('school/info/{slug}/report/allteacher-report/attendance/detail/month/search', 'School\TeacherReportController@attendanceMonthDetailList')->name('school.teacher.report.attendance.month.search');

	Route::get('school/info/{slug}/report/allteacher-report/salary/list', 'School\TeacherReportController@salaryList')->name('school.teacher.report.salary');
	Route::get('school/info/{slug}/report/allteacher-report/salary/search', 'School\TeacherReportController@salarySearchList')->name('school.teacher.report.salary.search');
	//student report
	Route::get('school/info/{slug}/report/allstudent-report/profile', 'School\StudentReportController@profileList')->name('school.student.report.profile');
	Route::get('school/info/{slug}/report/allstudent-report/profile/search', 'School\StudentReportController@profileSearchList')->name('school.student.report.profile.search');

	Route::get('school/info/{slug}/report/allstudent-report/attendance/list', 'School\StudentReportController@attendanceList')->name('school.student.report.attendance');
	Route::get('school/info/{slug}/report/allstudent-report/attendance/search', 'School\StudentReportController@attendanceSearchList')->name('school.student.report.attendance.search');
	Route::get('school/info/{slug}/report/allstudent-report/attendance/detail/{user_id}', 'School\StudentReportController@attendanceDetailList')->name('school.student.report.attendance.detail');
	Route::get('school/info/{slug}/report/allstudent-report/attendance/detail/month/search', 'School\StudentReportController@attendanceMonthDetailList')->name('school.student.report.attendance.month.search');




	// attendence
	Route::resource('school/info/{slug}/attendance/teacher-attendance', 'School\TeacherHasAttendanceController');
	Route::post('school/info/{slug}/attendance/teacher-attendance/getAllTeacherAttendance', 'School\TeacherHasAttendanceController@getAllTeacherAttendance')->name('getAllTeacherAttendance');
	Route::post('school/info/{slug}/attendance/teacher-attendance/getDateList', 'School\TeacherHasAttendanceController@getDateList')->name('getDateList');
	Route::post('school/info/{slug}/attendance/teacher-attendance/getTeacherList', 'School\TeacherHasAttendanceController@getTeacherList')->name('getTeacherList');

	//library
	Route::post('school/info/{slug}/library/book/getAllBook', 'School\BookController@getAllBook')->name('getAllBook');
	Route::resource('school/info/{slug}/library/book', 'School\BookController');

	// student
	Route::resource('school/info/{slug}/student', 'School\StudentController');
	Route::post('/school/info/{slug}/student/checkemail', 'School\StudentController@checkemail')->name('student_email.check');
	Route::post('/school/info/{slug}/student/checkrollno', 'School\StudentController@checkrollno')->name('student_rollno.check');
	Route::post('school/info/{slug}/student/getAllStudent', 'School\StudentController@getAllStudent')->name('getAllStudent');

	Route::post('school/info/{slug}/student/getClassList', 'School\StudentController@getClassList')->name('getClassList');
	Route::post('school/info/{slug}/student/getSectionList', 'School\StudentController@getSectionList')->name('getSectionList');
	Route::post('school/info/{slug}/student/getShiftTeacherList', 'School\StudentController@getShiftTeacherList')->name('getShiftTeacherList');
	Route::post('school/info/{slug}/student/getSectionBookList', 'School\StudentController@getSectionBookList')->name('getSectionBookList');
	Route::post('school/info/{slug}/student/getStudentNameList', 'School\StudentController@getStudentNameList')->name('getStudentNameList');
	Route::post('school/info/{slug}/student/getTeacherClassSalaryList', 'School\StudentController@getTeacherClassSalaryList')->name('getTeacherClassSalaryList');
	
	Route::get('school/info/{slug}/student/export/excel', 'School\StudentController@export')->name('student.export');
	Route::get('school/info/{slug}/student/detail/{id}', 'School\StudentController@detailPrint')->name('student.detail.print');
	Route::post('school/info/{slug}/student/getStudentCount', 'School\StudentController@getStudentCount')->name('student.getStudentCount');
	
	// attendance student
	Route::get('school/info/{slug}/student-attendance', 'School\StudentHasAttendanceController@index')->name('student-attendance.index');
	Route::post('school/info/{slug}/student-attendance/teacher-student-attendance', 'School\StudentHasAttendanceController@store')->name('teacher-student-attendance.store');
	// ajax student search
	Route::post('school/info/{slug}/student-attendance/getStudentList', 'School\StudentHasAttendanceController@getStudentList')->name('getStudentList');

	// mark
	// Route::post('primary-entry/student/getAllStudentMark', 'StudentHasMarkController@getAllStudentMark')->name('getAllStudentMark');
	// Route::post('primary-entry/student/getExamList', 'StudentHasMarkController@getExamList')->name('getExamList');
	Route::post('school/info/{slug}/exam/student/getExamShiftList', 'School\LedgerController@getExamShiftList')->name('getExamShiftList');
	Route::post('school/info/{slug}/exam/student/getExamClassList', 'School\LedgerController@getExamClassList')->name('getExamClassList');
	Route::post('school/info/{slug}/exam/student/getExamSectionList', 'School\LedgerController@getExamSectionList')->name('getExamSectionList');

	// ledger
	Route::get('school/info/{slug}/exam/student/mark/ledger', 'School\LedgerController@index')->name('studenthasmark.ledger');
	Route::get('school/info/{slug}/exam/student/ledger/mark/view', 'School\LedgerController@ledgerView')->name('studenthasmark.ledger.view');
	Route::get('school/info/{slug}/exam/student/ledger/mark/view/show', 'School\LedgerController@show')->name('studenthasmark.ledger.show');
	// 
	Route::get('school/info/{slug}/exam/student/mark/print/{std_slug}/{exam}', 'School\LedgerController@getMarkPrint')->name('studenthasmark.getMarkPrint');
	Route::get('school/info/{slug}/exam/student/grade/print/{std_slug}/{exam}', 'School\LedgerController@getGradePrint')->name('studenthasmark.getGradePrint');
	Route::get('school/info/{slug}/exam/student/both/print/{std_slug}/{exam}', 'School\LedgerController@getBothPrint')->name('studenthasmark.getBothPrint');
	Route::get('school/info/{slug}/exam/student/marksheet/print/{std_slug}/{exam}', 'School\LedgerController@getMarkSheetPrint')->name('studenthasmark.getMarkSheetPrint');
});