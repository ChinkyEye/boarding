<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\Teacher;
use App\TeacherIncome;
use App\User;
use App\Setting;
use App\Staff_has_bank;
use Response;
use Auth;

class TeacherIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teachers = TeacherIncome::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->get();
                        // dd($teachers);
        return view('backend.teacherincome.index', compact('teachers'));
    }

    public function create()
    {
        $teachers = Teacher::where('school_id', Auth::user()->school_id)
                              // ->where('batch_id', Auth::user()->batch_id)
                              ->whereHas('getTeacherFromBatch', function(Builder $query){
                                  $query->where('batch_id', Auth::user()->batch_id);
                                  })
                              ->get();
                        // dd($teachers);
        return view('backend.teacherincome.create', compact('teachers'));
    }

    public function getTeacherInfoList(Request $request){
      $find_teacher = Teacher::find($request->teacher_id);
      $teacherincomes = TeacherIncome::where('school_id', Auth::user()->school_id)
                          ->where('batch_id', Auth::user()->batch_id)
                          ->where('teacher_id',$request->teacher_id)
                          ->where('month',$request->month)->get();
      return view('backend.teacherincome.create-ajax', compact('find_teacher','request','teacherincomes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
       
        $this->validate($request, [
            'amount' => 'required',
        ]);

        $teacher_salary = $request->amount;
        $teacher_grade = $request->grade;

         //total salary after adding monthly salary and grade amount
        $total = $teacher_salary + $teacher_grade;

        //10% provident is calculated from total salary
        $fpf = (10/100)*$total;
        // $account->pf1 =$fpf ;

        //Provident fund calculation
        $pf2 = $fpf; 
        $totalpf = $fpf + $pf2;
        // $account->total_pf = $totalpf;

        //insurance amount 400 for every level of employee plus 400 from government and total is 800
        $teacher_insurance = 400;
        $teacher_tot_insurance =800;

        //total salary after adding maghi bhatta and durgam bhatta
        $TSL= $total + $fpf + $request->mahangi_vatta + $request->durgam_vatta + $teacher_insurance;
        $total2 = $TSL;

         $colAmt = $request->cloth_amount;

        //Gross salary after deducting total pf, citizen deduction and total insurance amount
        $gsalary = $TSL - $totalpf - $request->citizen_investment_deduction - $teacher_tot_insurance;
        $gros_slry = $gsalary;

          //Calculation of social security tax that is 1% from gross salary
        $socTax=(1/100)*$gsalary;

             //Calculating social security tax from cloth amount
        $colTax =(1/100)*$colAmt;
        $colRcv = $colAmt + $colTax;
        
        //Total scocial security tax amount
        $soc_sec_tax = $socTax+$colTax;
        
        //Calculation of annual salary
        $anualAmt = 12*$gsalary;

        //Calculation of salary tax and net salary on the base of condition
        if($anualAmt<450000) { 
            $sal_tax =0;
            $net_salary =$gsalary - $socTax;
        }
        elseif($anualAmt>450000 && $anualAmt<=550000) { 
            $taxable_amount = $anualAmt - 450000;   
            $anual_tax = (15/100)*$taxable_amount;
            $monthly_tax = $anual_tax/12;
            $sal_tax = $monthly_tax;
            $net_salary =$gsalary - $socTax - $monthly_tax; 
        }
        elseif($anualAmt>550000 && $anualAmt<=650000) {    
            $taxable_amount = $anualAmt - 450000;   
            $anual_tax = (25/100)*$taxable_amount;
            $monthly_tax = $anual_tax/12;
            $sal_tax = $monthly_tax;
            $net_salary =$gsalary - $socTax - $monthly_tax;
        }
        elseif($anualAmt>650000) {     
            $taxable_amount = $anualAmt - 450000;   
            $anual_tax = (35/100)*$taxable_amount;
            $monthly_tax = $anual_tax/12;
            $sal_tax = $monthly_tax;
            $net_salary =$gsalary - $socTax - $monthly_tax;
        }

         //Receivable amount after dudecting loan amount if taken
        $rcv_amount = $net_salary +$colRcv- $request->loan_deduction;
        
     

        $incomes= TeacherIncome::create([
          'teacher_id' => $request['teacher_id'],
          'user_id' => $request['user_id'],
          'date' => $request['date'],
          'month' => $request['month'],
          'amount' => $teacher_salary,
          'grade' => $teacher_grade,
          'insurance' => $request['insurance'],
          'mahangi_vatta' => $request['mahangi_vatta'],
          'durgam_vatta' => $request['durgam_vatta'],
          'citizen_investment_deduction' => $request['citizen_investment_deduction'],
          'loan_deduction' => $request['loan_deduction'],
          'cloth_amount' => $request['cloth_amount'],
          'pradyanadhyapak_bhattā' => $request['pradyanadhyapak_bhattā'],
          'chadparva_kharcha'=> $request['chadparva_kharcha'],
          'permanent_allowance' => $request['permanent_allowance'],
          'remark' => $request['remark'],
          'total1' => $total,
          'total2' => $total2,
          'pro_f1' => $fpf,
          'pro_f2' => $fpf,
          'total_pf' => $totalpf,
          'insurance' => $teacher_insurance,
          'total_insurance' => $teacher_tot_insurance,
          'gross_salary' => $gros_slry,
          'soc_sec_tax' => $soc_sec_tax,
          'sal_tax' => $sal_tax,
          'net_salary' => $net_salary,
          'soc_sec_tax' => $soc_sec_tax,
          'rcv_amount' => $rcv_amount,
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
          'message' => 'Salary added successfully!',
          'alert-type' => 'success'
        );
       return redirect()->route('admin.salary.index')->with($pass);

    }


    public function getTeacherDetailList(Request $request){
      // dd($request);
      $routine_list = TeacherIncome::where('school_id',Auth::user()->school_id)
                          ->where('teacher_id',$request->teacher_id)
                          ->where('month',$request->month)
                          ->count();
                          // dd($routine_list);
      $teacher = Teacher::where('id',$request->teacher_id)->value('user_id');
      $teacher_info = User::find($teacher);
      $month_name = $request->month;
      $date_name = $request->date;
      if($month_name == 1){
        $month = 'Baisakh';
      }
      elseif ($month_name == 2) {
        $month = 'Jestha';
      }
      elseif ($month_name == 3) {
        $month = 'Ashar';
      }
      elseif ($month_name == 4) {
        $month = 'Shrawan';
      }
      elseif ($month_name == 5) {
        $month = 'Bhadra';
      }
      elseif ($month_name == 6) {
        $month = 'Ashoj';
      }
      elseif ($month_name == 7) {
        $month = 'Kartik';
      }
      elseif ($month_name == 8) {
        $month = 'Mangsir';
      }
      elseif ($month_name == 9) {
        $month = 'Poush';
      }
      elseif ($month_name == 10) {
        $month = 'Magh';
      }
      elseif ($month_name == 11) {
        $month = 'Falgun';
      }
      else{
        $month = 'Chaitra';
      }
      if($routine_list){
        $response = array(
          'data' => $routine_list,
          'status' => 'error',
          'msg' => ' Salary is already inserted for '.$teacher_info->name.' '.$teacher_info->middle_name.' '.$teacher_info->last_name.' '.'Month :'.' '. $month,
        );
      }else{
        $response = array(
          'data' => $routine_list,
        );
      }
      return Response::json($response);
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
        $teacherincomes = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->get();
                            // dd($teacherincomes);
        return view('backend.teacherincome.edit', compact('teacherincomes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,TeacherIncome $salary)
    {
      $this->validate($request, [
        'amount' => 'required',
      ]);

      $main_data = $request->all();
      $main_data['updated_by'] = Auth::user()->id;
      if($salary->update($main_data)){
        $notification = array(
          'message' => $request->name.' Data updated successfully!',
          'alert-type' => 'success'
        );
      }else{
        $notification = array(
          'message' => $request->name.' Data could not be updated!',
          'alert-type' => 'error'
        );
      }
      return redirect()->route('admin.salary.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TeacherIncome $salary)
    {
        if($salary->delete()){
          $notification = array(
            'message' => 'Data deleted successfully!',
            'status' => 'success'
          );
        }else{
          $notification = array(
            'message' => 'Data could not be deleted!',
            'status' => 'error'
          );
        }
        return Response::json($notification);
    }

    public function NLKfund($id){
      $teacher_id = TeacherIncome::where('id', $id)->value('teacher_id');
      $staff_has_banks = Staff_has_bank::where('teacher_id',$teacher_id)->get();
      $teacherincomes = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->get();
      $school_id = Auth::user()->school_id;
      $school_info = Setting::find($school_id);
      $month_name = TeacherIncome::where('id', $id)->value('month');
      // dd($month_name);
      if($month_name == 1){
        $month = 'Baisakh';
      }
      elseif ($month_name == 2) {
        $month = 'Jestha';
      }
      elseif ($month_name == 3) {
        $month = 'Ashar';
      }
      elseif ($month_name == 4) {
        $month = 'Shrawan';
      }
      elseif ($month_name == 5) {
        $month = 'Bhadra';
      }
      elseif ($month_name == 6) {
        $month = 'Ashoj';
      }
      elseif ($month_name == 7) {
        $month = 'Kartik';
      }
      elseif ($month_name == 8) {
        $month = 'Mangsir';
      }
      elseif ($month_name == 9) {
        $month = 'Poush';
      }
      elseif ($month_name == 10) {
        $month = 'Magh';
      }
      elseif ($month_name == 11) {
        $month = 'Falgun';
      }
      else{
        $month = 'Chaitra';
      }

      return view('backend.teacherincome.laganikosh', compact('school_info','staff_has_banks','teacherincomes','month'));
    }

    public function ledger(){
      return view('backend.teacherincome.ledger');
    }

    public function getLedgerInfoList(Request $request){
      $teacherincomes = TeacherIncome::where('school_id', Auth::user()->school_id)
                          ->where('month',$request->month_id)->get();
      // dd($teacherincomes);
      return view('backend.teacherincome.ledger_ajax', compact('teacherincomes'));
    }

    public function journal($id){
      $teachers = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->get();
      return view('backend.teacherincome.journal',compact('teachers'));
    }

    public function pfLedger($id){
      $teachers = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->withCount('getTeacherBankInfo')
                                ->get();
      $settings = Setting::find(Auth::user()->school_id);  
      $teacher_income = TeacherIncome::find($id);
      $staff_has_banks = Staff_has_bank::where('teacher_id',$teacher_income->teacher_id)->get();
      // dd($teacher_income,$staff_has_banks);
      return view('backend.teacherincome.pf_ledger',compact('teachers','settings'));
    }

    public function farum($id){
      $teacher_id = TeacherIncome::where('id', $id)->value('teacher_id');
      $staff_has_banks = Staff_has_bank::where('teacher_id',$teacher_id)->get();
      $teacherincomes = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
      ->where('batch_id', Auth::user()->batch_id)
      ->get();
      $settings = Setting::find(Auth::user()->school_id); 
      // $try = date('Y');
      // dd($try);
      $dates = TeacherIncome::where('id', $id)->value('date');
      $year_date= Date('Y', strtotime($dates));
      // dd(Date('Y', strtotime($dates)));
      $month_name = TeacherIncome::where('id', $id)->value('month');
      // dd($dates->format('Y'));
      if($month_name == 1){
        $month = 'Baisakh';
      }
      elseif ($month_name == 2) {
        $month = 'Jestha';
      }
      elseif ($month_name == 3) {
        $month = 'Ashar';
      }
      elseif ($month_name == 4) {
        $month = 'Shrawan';
      }
      elseif ($month_name == 5) {
        $month = 'Bhadra';
      }
      elseif ($month_name == 6) {
        $month = 'Ashoj';
      }
      elseif ($month_name == 7) {
        $month = 'Kartik';
      }
      elseif ($month_name == 8) {
        $month = 'Mangsir';
      }
      elseif ($month_name == 9) {
        $month = 'Poush';
      }
      elseif ($month_name == 10) {
        $month = 'Magh';
      }
      elseif ($month_name == 11) {
        $month = 'Falgun';
      }
      else{
        $month = 'Chaitra';
      }
      return view('backend.teacherincome.talabmaagfarum', compact('settings','staff_has_banks','teacherincomes','month','year_date'));
    }

    public function gosawaraVoucher($id){
      $teacher_incomes = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
      ->where('batch_id', Auth::user()->batch_id)
      ->withCount('getTeacherBankInfo')
      ->get();
      $settings = Setting::find(Auth::user()->school_id);  
      return view('backend.teacherincome.gosawaravoucher',compact('settings','teacher_incomes'));
    }

    public function gosawaraVoucherKharcha($id){
      $teacher_incomes = TeacherIncome::where('id', $id)->where('school_id', Auth::user()->school_id)
      ->where('batch_id', Auth::user()->batch_id)
      ->withCount('getTeacherBankInfo')
      ->get();
      $settings = Setting::find(Auth::user()->school_id);  
      return view('backend.teacherincome.gosawaravoucherkharcha',compact('settings','teacher_incomes'));
    }
}
