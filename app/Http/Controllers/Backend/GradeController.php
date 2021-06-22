<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\Grade;
use Auth;
use Response;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $grades = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','DESC')->get();
        return view('backend.examsection.grade.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.examsection.grade.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id; 
        $this->validate($request, [
            'name' => 'required|min:1',
            'slug' => 'required|unique:grades|min:1',
            'max' => 'required',
            'min' => 'required',
            'grade_point' => 'required',
        ]);

        $grades = Grade::create([
            'name' => $request['name'],
            'slug' => $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id,
            'max' => $request['max'],
            'min' => $request['min'],
            'grade_point' => $request['grade_point'],
            'remark' => $request['remark'],
            'school_id' => Auth::user()->school_id,
'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.grade.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grades = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->get();
        return view('backend.examsection.grade.show', compact('grades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grades = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.examsection.grade.edit', compact('grades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        $check_dub = Grade::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
        if($check_dub == 0 || $request->name == $grade->name){
          $this->validate($request, [
              'name' => 'required|min:1',
              'max' => 'required',
              'min' => 'required',
              'grade_point' => 'required',
          ]);
          $main_data = $request->all();
          $main_data['updated_by'] = Auth::user()->id;
          if($grade->update($main_data)){
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
              'name' => 'required|unique:grades|min:1',
              'max' => 'required',
              'min' => 'required',
              'grade_point' => 'required',
          ]);
        }


        // $this->validate($request, [
        //     'name' => 'required',
        //     'max' => 'required',
        //     'min' => 'required',
        // ]);
        // $request['updated_by'] = Auth::user()->id;
        // if($grade->update($request->all())){
        //     $notification = array(
        //         'message' => 'Data updated successfully!',
        //         'alert-type' => 'success'
        //     );
        // }else{
        //     $notification = array(
        //         'message' => 'Data could not be updated!',
        //         'alert-type' => 'error'
        //     );
        // }

        return redirect()->route('admin.grade.index')->with($notification);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        if($grade->delete()){
            $notification = array(
                'message' => $grade->name.' is deleted successfully!',
                'status' => 'success'
            );
        }else{
            $notification = array(
                'message' => $grade->name.' could not be deleted!',
                'status' => 'error'
            );
        }
        return Response::json($notification);
    }

    public function isactive(Request $request,$id)
    {
        $get_is_active = Grade::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = Grade::find($id);
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
}
