<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\BillTotal;
use App\Bill;
use App\Book;
use Auth;
use Carbon\Carbon;



class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    // dd($request);
    $search = $request->search;
    $user_id = Auth::user()->id;
    $student_class = Student::where('user_id', $user_id)->pluck('class_id');
    $accounts = BillTotal::where('student_id', $user_id)
                           ->where('school_id',Auth::user()->school_id)
                           ->where('batch_id',Auth::user()->batch_id)
                           ->where('is_active','1')
                           ->orderBy('id','DESC');


    if($request->get('month') != ''){
        $accounts = $accounts->whereMonth('created_at',$request->month);
    }
    if($request->get('month') == 'undefined'){
        $date =Carbon::now()->month;
        $accounts = $accounts->whereMonth('created_at',$date);
    }

    if($request->get('status') != ''){
        $accounts = $accounts->where('bill_type',$request->status);
        $total_status = $accounts->count();
    }
    $accounts = $accounts->get();
    $total = $accounts->count();

    $billtotal = BillTotal::where('student_id', $user_id)
                                        ->where('school_id',Auth::user()->school_id)
                                        ->where('batch_id',Auth::user()->batch_id)
                                        ->orderBy('id','DESC')->get();
    $school_bill = $billtotal->where('is_active','1')->where('bill_type','1')->sum('total');
    $paid_bill = $billtotal->where('is_active','1')->where('bill_type','2')->sum('total');

    $remaining = $school_bill - $paid_bill;
    $response = [
        'accountlist' => $accounts,
        'totallist' => $total,
        'remainedlist' => $remaining,
    ];
    return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    public function show(Request $request, $slug)
    {
        $invoice_id = $slug;
        $user_id = Auth::user()->id;

        $accounts = Bill::where('student_id', $user_id)
                               ->where('school_id',Auth::user()->school_id)
                               ->where('batch_id',Auth::user()->batch_id)
                               ->where('invoice_id',$invoice_id)
                               ->orderBy('id','DESC');

        $accounts = $accounts->with('getTopic','getBillInfo','getUserInfo','getSchoolInfo','getUser','getStudent')->get();

        $response = [
            'accounts' => $accounts,
        ];
        return response()->json($response);
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
}
