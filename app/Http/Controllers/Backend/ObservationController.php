<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\Observation;
use Response;

class ObservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $observations = Observation::orderBy('id','DESC')->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->paginate(15);
        return view('backend.examsection.observation.index',compact('observations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.examsection.observation.create');
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
            'title' => 'required',
            'remark' => 'required',
            'value' => 'required',
        ]);
        // dd($request);
        
        $observations = Observation::create([
            'title' => $request['title'],
            'remark' => $request['remark'],
            'value' => $request['value'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.observation.create')->with($pass)->withInput();
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
        $observations = Observation::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.examsection.observation.edit', compact('observations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Observation $observation)
    {
        $this->validate($request, [
          'title' => 'required',
          'remark' => 'required',
          'value' => 'required',
        ]);

        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        if($observation->update($main_data)){
          $notification = array(
            'message' => $request->name.' Period is updated successfully!',
            'alert-type' => 'success'
          );
        }else{
          $notification = array(
            'message' => $request->name.' Period could not be updated!',
            'alert-type' => 'error'
          );
        }
        return redirect()->route('admin.observation.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Observation $observation)
    {
        if($observation->delete()){
          $notification = array(
            'message' => $observation->title.' is deleted successfully!',
            'status' => 'success'
          );
        }else{
          $notification = array(
            'message' => $observation->title.' could not be deleted!',
            'status' => 'error'
          );
        }
        return back()->with($notification);
         // return Response::json($notification);
    }
}
