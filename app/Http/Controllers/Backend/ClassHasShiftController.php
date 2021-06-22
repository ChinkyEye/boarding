<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use App\Class_has_shift;
use App\Shift;
use App\SClass;
use Auth;
use Response;


class ClassHasShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function main($slug)
    {
      $class_id = SClass::where('slug',$slug)->value('id'); 
      $class_name = SClass::where('slug',$slug)->value('name'); 
      $page = "Shift Class";
      $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      $classhasshifts = Class_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('class_id',$class_id)->get();
      return view('backend.mainentry.class_has_shift.index',compact('class_id','page','shifts','class_name','slug','classhasshifts'));
    }

    public function index()
    {
        
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = "Shift Class";
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
        return view('backend.mainentry.class_has_shift.create', compact('shifts','classes','page'));
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
            'class_id' => 'required',
            'shift_id' => 'required',
        ]);
        $class_id = $request->class_id;
        $shift_id = $request->shift_id;
        $data = Class_has_shift::where('class_id','=', $class_id)->where('shift_id','=', $shift_id)->where('school_id','=', Auth::user()->school_id)->count();
        if($data > 0)
        {
         $pass = array(
           'message' => 'Data already taken for the class!',
           'alert-type' => 'error'
         );
        }
        else
        {
         $class_has_shifts = Class_has_shift::create([
             'class_id' => $request['class_id'],
             'shift_id' => $request['shift_id'],
             'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
             'created_by' => Auth::user()->id,
             'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
         ]);
         $pass = array(
             'message' => 'Data added successfully!',
             'alert-type' => 'success'
         );
        }
        return redirect()->route('admin.class.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class_has_shifts = Class_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.mainentry.class_has_shift.show', compact('class_has_shifts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shifts = Shift::where('school_id',Auth::user()->school_id)->get();
        $classes = SClass::where('school_id',Auth::user()->school_id)->get();
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        // dd($shifts);
        $class_has_shifts = Class_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        // dd($class_has_shifts);
        return view('backend.mainentry.class_has_shift.edit', compact('class_has_shifts','shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Class_has_shift $c_has_shift)
    {
        $request['updated_by'] = Auth::user()->id;
        if($c_has_shift->update($request->all())){
            $notification = array(
                'message' => 'Data updated successfully!',
                'alert-type' => 'success'
            );
        }else{
            $notification = array(
                'message' => 'Data could not be updated!',
                'alert-type' => 'error'
            );
        }
        return redirect()->route('admin.class_has_shift', $c_has_shift->getClass->slug)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Class_has_shift $c_has_shift)
    {
        if($c_has_shift->delete()){
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
        return back()->with($notification)->withInput();
    }
    public function isactive(Request $request,$id)
    {
        $get_is_active = Class_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = Class_has_shift::find($id);
        if($get_is_active == 0){
            $isactive->is_active = 1;
            $notification = array(
                'message' => 'Data is published!',
                'alert-type' => 'success'
            );
        }
        else {
            $isactive->is_active = 0;
            $notification = array(
                'message' => 'Data could not be published!',
                'alert-type' => 'error'
            );
        }
        $isactive->update();
        return back()->with($notification)->withInput();
    }
}
