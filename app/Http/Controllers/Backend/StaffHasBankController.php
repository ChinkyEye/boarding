<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Teacher;
use App\User;
use App\Staff_has_bank;
use Auth;
use Response;

class StaffHasBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $teacher_id = Teacher::where('slug',$slug)->value('id');
        $staffhasbanks = Staff_has_bank::where('teacher_id',$teacher_id)->orderBy('created_at','DESC')->get();
        // dd($staffhasbanks);
        return view('backend.primaryentry.staff_has_bank.index', compact('teacher_id','staffhasbanks'));
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
        // dd($request);
        $this->validate($request, [
          'bank_name' => 'required',
          'account_no' => 'required',
          'bank_address' => 'required',
          'teacher_id' => 'required',
      ]);
        $teacher_id = $request->teacher_id;
        $user_id = Teacher::where('school_id',Auth::user()->school_id)->where('id',$teacher_id)->value('user_id');
        // dd($user_id);
        $staffhasbanks = Staff_has_bank::create([
            'user_id' => $user_id,
            'teacher_id' => $request['teacher_id'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'bank_name' => $request['bank_name'],
            'account_no' => $request['account_no'],
            'bank_address' => $request['bank_address'],
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),

        ]);
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
        );
        return back()->with($pass)->withInput();

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
    public function destroy(Staff_has_bank $staffhasbank)
    {
        if($staffhasbank->delete()){
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
}
