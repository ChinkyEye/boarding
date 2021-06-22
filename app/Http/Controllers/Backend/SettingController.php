<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\Setting;
use Response;

class SettingController extends Controller
{

    public function index()
    {
        // $settings=Setting::where('id', Auth::user()->school_id)->orderBy('id','DESC')->get();
        $settings = Setting::find(Auth::user()->school_id);
        return view('backend.setting.index',compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.setting.create');
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
            'school_name' => 'required',
            'address' => 'required',
            'phone_no' => 'required',
            'email' => 'required',
            'principal_name' => 'required',
        ]);
        // dd($request);

        $notices= Setting::create([
            'school_name' => $request['school_name'],
            'address' => $request['address'],
            'phone_no' => $request['phone_no'],
            'email' => $request['email'],
            'principal_name' => $request['principal_name'],
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.setting.index')->with($pass);
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
        $settings = Setting::where('id', Auth::user()->school_id)->where('id', $id)->get();
        return view('backend.setting.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $this->validate($request, [
            'school_name' => 'required',
            'address' => 'required',
            'phone_no' => 'required',
            'email' => 'required',
            'principal_name' => 'required',
        ]);

        $request['updated_by'] = Auth::user()->id;
        if($setting->update($request->all())){
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

        return redirect()->route('admin.setting.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        if($setting->delete()){
            $notification = array(
                'message' => $setting->school_name.' is deleted successfully!',
                'status' => 'success'
            );
        }else{
            $notification = array(
                'message' => $setting->school_name.' could not be deleted!',
                'status' => 'error'
            );
        }
        return Response::json($notification);
    }
}
