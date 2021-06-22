<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use App\Nationality;
use Auth;
use Response;
use App\Exports\NationalityExport;
use Maatwebsite\Excel\Facades\Excel;

class NationalityController extends Controller
{

    public function index()
    {
        $nationalities=Nationality::where('school_id',Auth::user()->school_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5);
        return view('backend.mainentry.nationality.index',compact('nationalities'));
    }

    public function export()
    {
        return Excel::download(new NationalityExport, 'users.xlsx');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mainentry.nationality.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request['slug'] = $this->helper->slug_converter($request['n_name']).'-'.Auth::user()->school_id;
        $this->validate($request, [
            'n_name' => 'required|min:2',
            'slug' => 'required|unique:nationalities|min:2',
        ]);

        $nationalities= Nationality::create([
            'n_name' => $request['n_name'],
            'slug' => $this->helper->slug_converter($request['n_name']).'-'.Auth::user()->school_id,
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
          'message' => 'Data added successfully!',
          'alert-type' => 'success'
        );
        return redirect()->route('admin.nationality.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nationalities = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.mainentry.nationality.show', compact('nationalities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nationalities = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.mainentry.nationality.edit', compact('nationalities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nationality $nationality)
    {
        $check_dub = Nationality::where('school_id',Auth::user()->school_id)->where('n_name',$request->n_name)->count();
        if($check_dub == 0 || $request->n_name == $nationality->n_name){
          $this->validate($request, [
              'n_name' => 'required|min:2',
          ]);
          $main_data = $request->all();
          $main_data['updated_by'] = Auth::user()->id;
          if($nationality->update($main_data)){
              $notification = array(
                  'message' => $request->n_name.' updated successfully!',
                  'alert-type' => 'success'
              );
          }else{
              $notification = array(
                  'message' => $request->n_name.' could not be updated!',
                  'alert-type' => 'error'
              );
          }
        }else{
          $this->validate($request, [
              'n_name' => 'required|unique:nationalities|min:2',
          ]);
        }
        return redirect()->route('admin.nationality.index')->with($notification)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nationality $nationality)
    {
      if($nationality->delete()){
        $notification = array(
          'message' => $nationality->n_name.' is deleted successfully!',
          'status' => 'success'
        );
      }else{
        $notification = array(
          'message' => $nationality->n_name.' could not be deleted!',
          'status' => 'error'
        );
      }
      // return back()->with($notification);
      return Response::json($notification);

    }

    public function isactive(Request $request,$id)
    {
      $get_is_active = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
      $isactive = Nationality::find($id);
      if($get_is_active == 0){
        $isactive->is_active = 1;
        $notification = array(
          'message' => $isactive->n_name.' is Active!',
          'alert-type' => 'success'
        );
      }
      else {
        $isactive->is_active = 0;
        $notification = array(
          'message' => $isactive->n_name.' is inactive!',
          'alert-type' => 'error'
        );
      }
      if(!($isactive->update())){
        $notification = array(
          'message' => $isactive->n_name.' could not be changed!',
          'alert-type' => 'error'
        );
      }
      return back()->with($notification)->withInput();
    }

    public function isSort(Request $request,$id)
    {
      $sort_ids =  Nationality::find($request->id);
      $sort_ids->sort_id = $request->value;
      if($sort_ids->save()){
        $response = array(
          'status' => 'success',
          'msg' => $sort_ids->n_name.' Successfully changed position to '.$request->value,
        );
      }else{
        $response = array(
          'status' => 'failure',
          'msg' => 'Sorry, '.$sort_ids->n_name.' could not change position to '.$request->value,
        );
      }
      return Response::json($response);
    }

}
