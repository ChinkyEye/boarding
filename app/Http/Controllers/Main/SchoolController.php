<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Helper\Helper;
use App\Setting;
use App\User;
use Auth;
use Response;
use Illuminate\Support\Facades\DB;


class SchoolController extends Controller
{
    public function index()
    {
      $page = "School";
      $settings = Setting::where('created_by', Auth::user()->id)
                          ->where('user_type',1)
                          ->orderBy('id','ASC')
                          ->get();
      return view('main.school', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $page = "Create School";
      $settings = Setting::where('created_by', Auth::user()->id)
                          ->where('user_type',1)
                          ->orderBy('id','ASC')
                          ->get();
      return view('main.school_create',compact(['settings','page']));
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request['slug'] = $this->helper->slug_converter($request['school_name']).'-'.Auth::user()->school_id; 
      $request->validate([
                          'school_name' => 'required',
                          'slug' => 'required|unique:settings|min:2',
                          'address' => 'required',
                          'phone_no' => 'required',
                          'email' => 'required|unique:settings',
                          'principal_name' => 'required',
                          'url' => 'required',
                          'image' => 'required',
                          'school_code' => 'required|unique:settings',
                          'type_of_school' => 'required',
                          'level' => 'required',
                          'running_class' => 'required',
                        ]);
     
      $data = Setting::where('school_name','=', $request->school_name)->count();
      if($data > 0)
      {
        $pass = array(
                      'message' => $request->school_name.' has been already registered.',
                      'alert-type' => 'error'
                    );
      }
      else{
        $uppdf = $request->file('image');
        if($uppdf != ""){
            $extension = $uppdf->getClientOriginalExtension();
            $fileName = md5(mt_rand()).'.'.$extension;
        }
        $schools = Setting::create([
                                    'user_type' => '1',
                                    'school_name' => $request['school_name'],
                                    'slug' => $this->helper->slug_converter($request['school_name']).'-'.Auth::user()->id,
                                    'address' => $request['address'],
                                    'phone_no' => $request['phone_no'],
                                    'email' => $request['email'],
                                    'school_code' => $request['school_code'],
                                    'type_of_school' => $request['type_of_school'],
                                    'level' => $request['level'],
                                    'running_class' => $request['running_class'],
                                    'principal_name' => $request['principal_name'],
                                    'url' => $request['url'],
                                    'image' => $fileName,
                                    'created_by' => Auth::user()->id,
                                    'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
                                  ]);
        if($uppdf != ""){
          $destinationPath = 'images/main/'.$schools->slug;
          $uppdf->move($destinationPath, $fileName);
          $file_path = $destinationPath.'/'.$fileName;
        }

        $pass = array(
                      'message' => $schools->school_name.' has been registered.',
                      'alert-type' => 'success'
                    );
      }
      return redirect()->route('main.school.index')->with($pass)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
      $settings_id = Setting::where('slug', $slug)->value('id');
      $data_list = Setting::where('created_by', Auth::user()->id)
                          ->where('user_type',1)
                          ->orderBy('id','ASC');
      $settings = $data_list->get();
      $settings_show = $data_list->where('id', $settings_id)->get();
      return view('main.school_show', compact('settings','settings_show'));
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
    public function destroy(Setting $school)
    {
      try{
        return DB::transaction(function() use ($school)
        {
          $data_id= $school->id;
          $user_id = User::where('school_id',$data_id)->value('id');
          $delete_user = User::find($user_id);

          if($delete_user->delete()){
            $notification = array(
              'message' => $delete_user->name.' is deleted successfully!',
              'status' => 'success'
            );
          }else{
            $notification = array(
              'message' => $delete_user->name.' could not be deleted!',
              'status' => 'error'
            );
          }
          if($school->delete()){
            $notification = array(
              'message' => $school->school_name.' is deleted successfully!',
              'status' => 'success'
            );
          }else{
            $notification = array(
              'message' => $school->school_name.' could not be deleted!',
              'status' => 'error'
            );
          }
          return Response::json($notification);
        });
      }catch(\Exception $e){
        DB::rollback();
        dd($e);
      }
      DB::commit();

        // dd($school);
        // if($school->delete()){
        //     $notification = array(
        //                           'message' => $school->school_name.' is deleted successfully!',
        //                           'status' => 'success'
        //                         );
        // }else{
        //     $notification = array(
        //                           'message' => $school->school_name.' could not be deleted!',
        //                           'status' => 'error'
        //                         );
        // }
        // // return redirect()->route('main.main.index')->with($notification);
        // return Response::json($notification);
    }

    public function isactive(Request $request,$id)
    {
      $get_is_active = Setting::where('id',$id)->value('is_active');
      $isactive = Setting::find($id);
      if($get_is_active == 0){
        $isactive->is_active = 1;
        $notification = array(
                              'message' => $isactive->school_name.' is Active!',
                              'alert-type' => 'success'
                            );
      }
      else {
        $isactive->is_active = 0;
        $notification = array(
                              'message' => $isactive->school_name.' is inactive!',
                              'alert-type' => 'error'
                            );
      }
      if(!($isactive->update())){
        $notification = array(
                              'message' => $isactive->school_name.' could not be changed!',
                              'alert-type' => 'error'
                            );
      }
      return back()->with($notification)->withInput();
    }
    public function createadmin(Response $request)
    {
        dd($request);
    }
}
