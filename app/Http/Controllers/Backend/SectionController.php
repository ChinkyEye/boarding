<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Helper\Helper;
use Validator;
use Auth;
use App\Section;
use Response;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::where('school_id',Auth::user()->school_id)->orderBy('sort_id','ASC')->get();
        return view('backend.mainentry.section.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mainentry.section.create');
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
      $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id;
      // dd($request['slug']);
      $this->validate($request, [
          'name' => 'required|min:1',
          'slug' => 'required|unique:sections|min:2',
      ]);
      $sections= Section::create([
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
      return redirect()->route('admin.section.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->get();
        return view('backend.mainentry.section.edit', compact('sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {

      $check_dub = Section::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
      if($check_dub == 0 || $request->name == $section->name){
        $this->validate($request, [
            'name' => 'required|min:1',
        ]);
        $main_data = $request->all();
        $main_data['updated_by'] = Auth::user()->id;
        if($section->update($main_data)){
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
            'name' => 'required|unique:sections|min:2',
        ]);
      }
      return redirect()->route('admin.section.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Section $section)
    {
      if($section->delete()){
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
        $sort_ids = Section::find($request->id);
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
    
    public function isActive($id)
    {
        $get_is_active = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = Section::find($id);
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
