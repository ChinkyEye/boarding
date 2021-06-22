<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Validator;
use App\Period;
use Auth;
use Response;

class PeriodController extends Controller
{

    public function index()
    {
      $periods=Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
      return view('backend.mainentry.period.index',compact('periods'));
    }

    public function create()
    {
      $periods=Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
      return view('backend.mainentry.period.create',compact('periods'));
    }

    public function store(Request $request)
    {
        $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id;
        $this->validate($request, [
          'name' => 'required',
          'slug' => 'required',
          // 'slug' => 'required|unique:periods|min:2',
          'start_time' => 'required',
          'end_time' => 'required',
        ]);

        $periods= Period::create([
            'name' => $request['name'],
            'slug' => $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id,
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
        );
        return redirect()->route('admin.period.index')->with($pass)->withInput();
    }

    public function show($id)
    {
      $periods=Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
      return view('backend.mainentry.period.show', compact('periods'));
    }

    public function edit($id)
    {
      $periods = Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
      return view('backend.mainentry.period.edit', compact('periods'));
    }

    public function update(Request $request, Period $period)
    {
      $check_dub = Period::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
      if($check_dub == 0 || $request->name == $period->name){
        $this->validate($request, [
          'name' => 'required|min:2',
          'start_time' => 'required',
          'end_time' => 'required',
        ]);
        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        if($period->update($main_data)){
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
            'name' => 'required|unique:periods|min:2',
        ]);
      }
      return redirect()->route('admin.period.index')->with($notification);
    }

    public function destroy(Period $period)
    {
       if($period->delete()){
         $notification = array(
           'message' => $period->name.' is deleted successfully!',
           'status' => 'success'
         );
       }else{
         $notification = array(
           'message' => $period->name.' could not be deleted!',
           'status' => 'error'
         );
       }
        return Response::json($notification);
    }

    public function isSort(Request $request,$id)
    {
      $sort_ids =  Period::find($request->id);
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
      $get_is_active = Period::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
      $isactive = Period::find($id);
      if($get_is_active == 0){
        $isactive->is_active = 1;
        $notification = array(
          'message' => $isactive->name.' Period is Active!',
          'alert-type' => 'success'
        );
      }
      else {
        $isactive->is_active = 0;
        $notification = array(
          'message' => $isactive->name.' Period is inactive!',
          'alert-type' => 'error'
        );
      }
      if(!($isactive->update())){
        $notification = array(
          'message' => $isactive->name.' Period could not be changed!',
          'alert-type' => 'error'
        );
      }
      return back()->with($notification)->withInput();
    }

}
