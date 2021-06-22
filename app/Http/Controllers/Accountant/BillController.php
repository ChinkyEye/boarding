<?php

namespace App\Http\Controllers\Accountant;

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
use App\Student;
use App\SClass;
use App\Section;
use App\Setting;
use App\User;
use Response;
use Riskihajar\Terbilang\Facades\Terbilang;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active', True)->get(); //change gareko
        return view('account.bill.index',compact('shifts'));
    }

    public function main($slug)
    {
      $student = Student::where('slug',$slug)->value('id');
      $student_id = Student::where('slug',$slug)->value('user_id'); 
      // dd($student_id);
      $user_info = User::find($student_id);
      // dd($user_info);
      $student_info = Student::find($student);
      // dd($student_info);
      $shift_id = Student::where('slug',$slug)->value('shift_id');

      $shift = Shift::where('id',$shift_id)->value('name'); 
      $class_id = Student::where('slug',$slug)->value('class_id'); 
      $class = SClass::where('id',$class_id)->value('name'); 
      $section_id = Student::where('slug',$slug)->value('section_id'); 
      // dd($section_id);
      $section = Section::where('id',$section_id)->value('name'); 
      $topics = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('class_id',$class_id)->where('is_active','1')->get(); //change gareko 

      return view('account.bill.create',compact('student_id','topics','user_info','student_info','shift','class','section'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = Topic::get();
        // dd($topics);
        return view('account.bill.create',compact('topics'));
    }

    public function getStudentBillList(Request $request){
      $all_data = json_decode($request->parameters, true);
      $datas = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','ASC'); //change gareko
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
      $getstudent_id = Student::where('shift_id',$all_data['shift_id'])->where('class_id',$all_data['class_id'])->where('section_id',$all_data['section_id'])->get(); 
      return view('account.bill.student_ajax', compact('datas','shifts','classes','sections'));
    }

    public function getBillCalculateList(Request $request)
    {
        // dd($request);
        // dd($request->data_id);
       $total_fee = 0;
        $topic_id = $request->topic_id;
         foreach( $topic_id AS $topic ){
          $fee = Topic::where('id', $topic)->value('fee');
           $total_fee += $fee;
        }
        return view('account.bill.bill_calculation',compact('total_fee'));    
    }


    public function getBillFeeList(Request $request)
    {
        // dd($request);
        // dd($request->data_id);
        $item_id = $request->item_id;
        $fee= Topic::where("id",$item_id)->value('fee');
        $topic= Topic::where("id",$item_id)->value('topic');
        return view('account.bill.bill_row',compact('item_id','fee','topic'));    
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
        ]);
        // dd($request->discount);
        $calc_bill_id = strtotime(date(("Y-m-d H:i:s")));
        $topic_id = $request->input('topic_id');
        $item_id = $request->input('item_id');
        $amount = $request->input('amount');
        // dd($request);
        $user_id = Student::where('user_id',$request['student_id'])->value('id');
        $total_fee = 0;
         foreach( $topic_id AS $topic ){
          $bills= Bill::create([
              'student_id' => $request['student_id'],
              'amount' => Topic::where('id', $topic)->value('fee'),
              'topic_id' => $topic,
              'invoice_id' => $calc_bill_id,
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
          ]);
         $total_fee += $bills->amount;
        }
        $discount = $request->discount;
        $fine = $request->fine;
        $nettotal = $total_fee - $discount + $fine;
        $try = $request->discount;
        // dd($request->discount);
        // dd($nettotal,$request->discount,$request->fine);
        
        $billtotal = BillTotal::create([
          'student_id' => $request['student_id'],
          'user_id' => $user_id,
          'total' => $total_fee,
          'invoice_id' => $calc_bill_id,
          'discount' =>  $try,
          'fine' =>  $request['fine'],
          'nettotal' =>  $nettotal,
          'bill_type' =>  '1',
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'bill_date' => $this->helper->date_np_con(),
          'bill_date_en' => $this->helper->date_eng_con(),
          'bill_time' => date("H:i:s"),
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        // dd($request,$billtotal);
         return redirect()->route('account.bill.print',['bill_id' => $calc_bill_id]);
    }

    public function print($bill_id){
      $settings = Setting::get();
      $data_id = BillTotal::where("invoice_id",$bill_id)->value('id');
      $bill_total_student = BillTotal::find($data_id);
      $student_id = BillTotal::where('invoice_id',$bill_id)->value('student_id'); 
      $bill_published = BillTotal::where("invoice_id",$bill_id)->value('is_published');
      $datas = User::find($student_id);
      return view('account.bill.print',compact('settings','student_id','bill_id','bill_total_student','bill_published','datas'));
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
