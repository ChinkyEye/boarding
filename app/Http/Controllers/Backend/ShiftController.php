<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\Shift;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShiftsExport;
use PDF;

class ShiftController extends Controller
{
 


  public function shiftExport() 
  {
   return Excel::download(new ShiftsExport, 'shifts.xlsx');
  } 


  public function index()
  {
    $shifts=Shift::where('school_id',Auth::user()->school_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
    return view('backend.mainentry.shift.index',compact('shifts'));
  }

  public function createPDF() {
    $data = Shift::where('school_id',Auth::user()->school_id)->where('batch_id',Auth::user()->batch_id)->get();
    view()->share('shifts',$data);
    $pdf = PDF::loadView('backend.mainentry.shift.pdf_view', $data);
    return $pdf->download('shift.pdf');
  }

  public function create()
  {
    return view('backend.mainentry.shift.create');
  }

  public function store(Request $request)
  {
    $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id;
    $this->validate($request, [
        'name' => 'required',
        'slug' => 'required|unique:shifts|min:2',
    ]);

    $shifts = Shift::create([
      'name' => $request['name'],
      'slug' => $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id,
      'school_id' => Auth::user()->school_id,
      'batch_id' => Auth::user()->batch_id,
      'created_by' => Auth::user()->id,
      'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
    ]);
    $pass = array(
      'message' => 'Data added successfully!',
      'alert-type' => 'success'
    );
    return redirect()->route('admin.shift.index')->with($pass)->withInput();
  }

  public function show($id)
  {
  	
  }

  public function edit($id)
  {
    $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
    return view('backend.mainentry.shift.edit', compact('shifts'));
  }


  public function update(Request $request, Shift $shift)
  {
    $check_dub = Shift::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
    if($check_dub == 0 || $request->name == $shift->name){
      $this->validate($request, [
          'name' => 'required|min:2',
      ]);
      $main_data = $request->all();
      $main_data['updated_by'] = Auth::user()->id;
      if($shift->update($main_data)){
          $notification = array(
              'message' => $request->name.' updated successfully!',
              'alert-type' => 'success'
          );
      }else{
          $notification = array(
              'message' => $request->name.' could not be updated!',
              'alert-type' => 'error'
          );
      }
    }else{
      $this->validate($request, [
          'name' => 'required|unique:shifts|min:2',
      ]);
    }
    return redirect()->route('admin.shift.index')->with($notification);
  }
  

  public function destroy(Shift $shift)
  {
    if($shift->delete()){
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

  public function isSort(Request $request,$id)
  {
    $sort_ids =  Shift::find($request->id);
    $sort_ids->sort_id = $request->value;
    if($sort_ids->save()){
      $response = array(
        'status' => 'success',
        'msg' => $sort_ids->name.' Successfully changed position to '.$request->value,
      );
    }else{
      $response = array(
        'status' => 'failure',
        'msg' => 'Sorry, '.$sort_ids->name.' could not change position to '.$request->value,
      );
    }
    return Response::json($response);
  }

  public function isactive(Request $request,$id)
  {
    $get_is_active = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
    $isactive = Shift::find($id);
    if($get_is_active == 0){
      $isactive->is_active = 1;
      $notification = array(
        'message' => $isactive->name.' is Active!',
        'alert-type' => 'success'
      );
    }
    else {
      $isactive->is_active = 0;
      $notification = array(
        'message' => $isactive->name.' is inactive!',
        'alert-type' => 'error'
      );
    }
    if(!($isactive->update())){
      $notification = array(
        'message' => $isactive->name.' could not be changed!',
        'alert-type' => 'error'
      );
    }
    return back()->with($notification)->withInput();
  }
}
