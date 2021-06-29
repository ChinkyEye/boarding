<?php

namespace App\Http\Controllers\Backend\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Auth;
use App\Shift;
use App\BillTotal;
use App\Exports\BillTotalsExport;
use Maatwebsite\Excel\Facades\Excel;

class FeeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd("bitch");
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->orderBy('sort_id','ASC')
                        ->get();
        return view('backend.accountsection.fee.report.index',compact(['shifts']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // var_dump("expression");
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active', True)
                        ->orderBy('sort_id','ASC')
                        ->get();

        $fee_all = BillTotal::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                ->where('is_active', True)
                                ->orderBy('id', 'DESC');
        $fee_list = $fee_all->get();
        return view('backend.accountsection.fee.report.create',compact(['shifts','fee_list']));
    }

    public function export(Request $request)
    {
        // dd($request);
      $excShift = $request->excShift;
      $excClass = $request->excClass;
      $excSection = $request->excSection;
      $excStudent = $request->excStudent;
      $excDateFrom = $request->excDateFrom;
      $excDateTo = $request->excDateTo;
      $excType = $request->excType;
      return Excel::download(new BillTotalsExport($excShift,$excClass,$excSection,$excStudent,$excDateFrom,$excDateTo,$excType), 'BillTotals.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    public function getReportFee(Request $request)
    {
        $shift_data = $request->shift_data;
        $section_data = $request->section_data;
        $class_data = $request->class_data;
        $student_data = $request->student_data;
        $filter_date_to = $request->filter_date_to;
        $filter_date_from = $request->filter_date_from;
        $type_data = $request->type_data;
if($type_data == '3'){
    $fee_list = BillTotal::groupBy('user_id','bill_type')
                ->where('bill_date','<=',$filter_date_from)
                ->orderBy('user_id','DESC');
                // dd($fee_list->get());
        
        if(!empty($shift_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($shift_data) {
                              $query->where('shift_id', $shift_data);
                          });
        }
        if(!empty($section_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($section_data) {
                              $query->where('section_id', $section_data);
                          });
        }
        if(!empty($class_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($class_data) {
                              $query->where('class_id', $class_data);
                          });
        }
        if(!empty($student_data)){
            $fee_list = $fee_list->where('user_id',$student_data);
        }
    $posts = $fee_list->selectRaw('bill_totals.id id, SUM(bill_totals.total) btt,SUM(bill_totals.fine) btf, SUM(bill_totals.discount) btd, SUM(bill_totals.nettotal) btnt, bill_totals.bill_type bt,users.name as fname, users.middle_name as mname, users.last_name as lname, users.id user_id,students.student_code user_code')
            ->where('is_published','1')
            ->where('bill_totals.is_active','1')
            ->join('users','bill_totals.student_id','=','users.id')
            ->join('students','bill_totals.user_id','=','students.id')
            ->get();
    return view('backend.accountsection.fee.report.unpaid',compact(['posts']));
}
        $fee_list = BillTotal::where('school_id',Auth::user()->school_id)
                    ->where('batch_id', Auth::user()->batch_id)
                    ->where('bill_type',$type_data)
                    ->where('is_active','1')
                    ->whereBetween('bill_date', [$filter_date_to, $filter_date_from])
                    ->orderBy('id','DESC');
                    
        if(!empty($shift_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($shift_data) {
                              $query->where('shift_id', $shift_data);
                          });
        }
        if(!empty($section_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($section_data) {
                              $query->where('section_id', $section_data);
                          });
        }
        if(!empty($class_data)){
            $fee_list = $fee_list->whereHas('getUserInfo', function (Builder $query) use ($class_data) {
                              $query->where('class_id', $class_data);
                          });
        }
        if(!empty($student_data)){
            $fee_list = $fee_list->where('user_id',$student_data);
        }
        $total_fee = $fee_list->sum('total');
        $net_total_fee = $fee_list->sum('nettotal');
        $total_discount = $fee_list->sum('discount');
        $total_fine = $fee_list->sum('fine');
        $fee_list = $fee_list->with('getStudentinfo')->paginate(50);
        return view('backend.accountsection.fee.report.create',compact(['fee_list','total_fee','total_discount','total_fine','net_total_fee']));
    }
}
