<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\Bill;
use App\BillTotal;
use App\Topic;
use App\Shift;
use App\Setting;
use App\Student;
use App\SClass;
use App\Section;
use App\Fee;
use App\User;
use Response;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Database\Eloquent\Builder;


class FeeController extends Controller
{
   
    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        return view('backend.accountsection.fee.index',compact('shifts'));
    }



    public function main($slug)
    {
      $settings = Setting::get();

      $student_id = Student::where('slug',$slug)->value('user_id');
      $find_student = Student::where('slug',$slug)->value('id');

      // dd($student_id);
      $student_info =Student::find($find_student);
      // dd($student_info);
      $invoice_bill_all = BillTotal::where('student_id',$student_id)->where('is_active', 1)->get(); 
      $invoice_bill_all_sum = BillTotal::where('student_id',$student_id)->where('is_active', 1)->where('bill_type', 1)->sum('nettotal'); 
      $invoice_bill_paid_sum = Fee::where('is_active', 1)->where('student_id',$student_id)->sum('amount_received');
      $invoice_bill_discount_sum = Fee::where('is_active', 1)->where('student_id',$student_id)->sum('discount');
      $invoice_bill_fine_sum = Fee::where('is_active', 1)->where('student_id',$student_id)->sum('fine');
      $amount_remain_total = $invoice_bill_all_sum + $invoice_bill_fine_sum - ($invoice_bill_paid_sum + $invoice_bill_discount_sum);
      $amount_words = Terbilang::make($amount_remain_total);
      // dd($amount_words);
      $invoice_paid_bill_all = Fee::where('student_id',$student_id)->where('is_active', 1)->get()->take(5);
      return view('backend.accountsection.fee.create',compact('settings','student_id','invoice_bill_all','invoice_bill_all_sum','invoice_bill_paid_sum','amount_remain_total','invoice_paid_bill_all','amount_words','student_info'));
    }

    public function getFee($slug)
    {
      $student_id = Student::where('slug',$slug)->value('id');
      $student_info = Student::find($student_id);
      // bill list made for student
      $bills = Bill::where('student_id',$student_info->user_id)->where('is_active','1')->get();
      // fee paid
      $fees = Fee::where('student_id',$student_info->user_id)->where('is_active','1')->get();
      $inactivefees = Fee::where('student_id',$student_info->user_id)->where('is_active','0')->get();
      // dd($inactivefees);
      return view('backend.accountsection.fee.viewfee',compact('student_info','bills','fees','inactivefees'));
    }

    public function getStudentFeeList(Request $request){
      $all_data = json_decode($request->parameters, true);
      // for invocies
      if($all_data['invoice_id']){
        $bills = BillTotal::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('invoice_id',$all_data['invoice_id'])->value('student_id'); 
        $user_datas = User::find($bills);

        return view('backend.accountsection.fee.bill_user_ajax', compact('user_datas'));
      }

      // for shift, class, section
      $datas = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','ASC'); 
      if($all_data['shift_id']){
        $datas =  $datas->where('shift_id', $all_data['shift_id']);
      }
      if($all_data['class_id']){
        $datas = $datas->where('class_id',$all_data['class_id']);
      }
      if($all_data['section_id']){
        $datas = $datas->where('section_id',$all_data['section_id']);
      }
      $datas = $datas->get();

      $shifts = Shift::where('id',$all_data['shift_id'])->value('name');
      $classes = SClass::where('id',$all_data['class_id'])->value('name');
      $sections = Section::where('id',$all_data['section_id'])->value('name');

      $class_id = SClass::where('id',$all_data['class_id'])->value('id');
      $getstudent_id = Student::where('id',$all_data['shift_id'])->where('id',$all_data['class_id'])->value('id'); 

      return view('backend.accountsection.fee.student_ajax', compact('datas','shifts','classes','sections'));
    }

    public function getAmountCheckList(Request $request){
      // dd($request->item_id,$request->amount_received);
      $totalamount = $request->item_id;
      $amountreceived = $request->amount_received;
      // dd($amountreceived,$totalamount);
      if($amountreceived > $totalamount){
       $pass = array(
         'message' => 'error!',
         'alert-type' => 'error'
       );
      }else{
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
        );
      }

      return back()->with($pass)->withInput();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student_id = Student::where('slug',$slug)->value('user_id'); 
        return view('backend.accountsection.fee.create',compact('student_id'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
          'student_id' => 'required',
          'amount_received' => 'required|numeric',
          'amount_remained' => 'required',
          'discount' => 'required|numeric',
          'fine' => 'required|numeric',
      ]);
      $calc_bill_id = strtotime(date(("Y-m-d H:i:s")));
      $user_id = Student::where('user_id',$request['student_id'])->value('id');
      $fees= Fee::create([
        'student_id' => $request['student_id'],
        'amount_received' => $request['amount_received'],
        'amount_remained' => $request['amount_remained'],
        'discount' => $request['discount'],
        'fine' => $request['fine'],
        'invoice_id' => $calc_bill_id,
        'school_id' => Auth::user()->school_id,
        'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);
      $billtotal= BillTotal::create([
        'student_id' => $request['student_id'],
        'user_id' => $user_id,
        'total' => $request['amount_received'],
        'nettotal' => $request['amount_received'],
        'discount' => $request['discount'],
        'fine' => $request['fine'],
        'invoice_id' => $calc_bill_id,
        'bill_date' => $this->helper->date_np_con(),
        'bill_date_en' => $this->helper->date_eng_con(),
        'bill_time' => date("H:i:s"),
        'bill_type' => '2',
        'school_id' => Auth::user()->school_id,
        'batch_id' => Auth::user()->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);
      $pass = array(
        'message' => 'Amount has been deducted !',
        'alert-type' => 'success'
      );
      return redirect()->route('admin.bill.print',['bill_id' => $fees->invoice_id])->with($pass)->withInput();
    }


    public function isactive(Request $request,$id)
    {
      $get_is_active = Fee::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
      $isactive = Fee::find($id);

      $invoice_id = $isactive->invoice_id;
      $billtotal = BillTotal::where('invoice_id',$invoice_id)->value('id');
      $billtotalisactive = BillTotal::find($billtotal);
      // dd($invoice_id,$billtotal,$billtotalisactive);
      $billtotalisactive->is_active = 0;
      $billtotalisactive->update();

      if($get_is_active == 1){
        $isactive->is_active = 0;
        $notification = array(
          'message' => $isactive->invoice_id.' is Deleted!',
          'alert-type' => 'success'
        );
      }
     
      if(!($isactive->update())){
        $notification = array(
          'message' => $isactive->invoice_id.' could not be changed!',
          'alert-type' => 'error'
        );
      }
      return back()->with($notification)->withInput();
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

   
}
