<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use Auth;
use Validator;
use App\User;
use App\Batch;
use App\UserHasBatch;
use Response;

class ProfileController extends Controller
{

    public function index()
    {
        $profiles = User::where('school_id', Auth::user()->school_id)
                    ->where('batch_id', Auth::user()->batch_id)
                    ->where('user_type','1')
                    ->where('is_active', True)
                    ->orderBy('id','DESC')
                    ->get();
        $batches = Batch::orderBy('id','DESC')
                    ->where('is_active', True)
                    ->get();
        return view('backend.profile.index',compact('profiles','batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        var_dump("expression"); die();
        return view('backend.setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $setting)
    {
        // var_dump("expression"); die();
        $this->validate($request, [
            'batch_id' => 'required',
        ]);

        // $ids = Auth::user()->id;
        // $user_data = User::find($ids);

        // $request['id'] = Auth::user()->id;
        if($setting->where('id',Auth::user()->id)->update(['batch_id'=>$request['batch_id']])){
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
        $datas = new UserHasBatch;
        $datas->user_id = Auth::user()->id;
        $datas->batch_id = $request['batch_id'];
        $datas->created_by=Auth::user()->id;
        $datas->created_at_np = $this->helper->date_np_con()." ".date("H:i:s");
        $datas->save();


        return redirect()->route('admin.profile.index')->with($notification);
    }

    // public function stores(Request $request)
    // {
    //     var_dump("expression"); die();
    //     $this->validate($request, [
    //         'school_name' => 'required',
    //         'address' => 'required',
    //         'phone_no' => 'required',
    //         'email' => 'required',
    //         'principal_name' => 'required',
    //     ]);
    //     // dd($request);

    //     $notices= Setting::create([
    //         'school_name' => $request['school_name'],
    //         'address' => $request['address'],
    //         'phone_no' => $request['phone_no'],
    //         'email' => $request['email'],
    //         'principal_name' => $request['principal_name'],
    //         'created_by' => Auth::user()->id,
    //         'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
    //     ]);
    //     $pass = array(
    //         'message' => 'Data added successfully!',
    //         'alert-type' => 'success'
    //     );
    //     return redirect()->route('admin.setting.index')->with($pass);
    // }

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
