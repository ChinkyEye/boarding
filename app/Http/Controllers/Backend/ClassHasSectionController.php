<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use App\Class_has_section;
use App\Class_has_shift;
use App\Shift;
use App\SClass;
use App\Section;
use Auth;
use Response;

class ClassHasSectionController extends Controller
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
      $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      // class shift
      $shifts = Class_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('class_id', $class_id)->where('is_active','1')->get();

      $class_has_sections=Class_has_section::where('school_id',Auth::user()->school_id)->where('class_id',$class_id)->orderBy('id','DESC')->paginate(5);
      return view('backend.mainentry.class_has_section.index',compact('class_id','page','sections','class_name','shifts','class_has_sections','slug'));
    }
    
    public function index()
    {
      $page = "Class Section";
      $class_has_sections=Class_has_section::where('school_id',Auth::user()->school_id)->orderBy('id','DESC')->paginate(5);
      return view('backend.mainentry.class_has_section.index',compact('class_has_sections','page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShiftList(Request $request){
      $data_id = $request->input('data_id');
      $shift_list_data = Shift::where('school_id',Auth::user()->school_id)->whereHas('getShiftList', function (Builder $query) use ($data_id) {
          $query->where('class_id', $data_id);
      })
      ->get();
      return Response::json($shift_list_data);
    }

    public function create()
    {
      $page = "Class Section";
      $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      return view('backend.mainentry.class_has_section.create', compact('page','classes','sections'));
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
            'section_id' => 'required',
        ]);

        $class_id = $request->class_id;
        $shift_id = $request->shift_id;
        $section_id = $request->section_id;
        $data = Class_has_section::where('class_id','=', $class_id)->where('shift_id','=', $shift_id)->where('section_id','=', $section_id)->where('school_id','=', Auth::user()->school_id)->count();
        // dd($data);
        if($data > 0)
        {
         $pass = array(
           'message' => 'Data already taken for the class!',
           'alert-type' => 'error'
         );
        }
        else
        {
         $class_has_sections = Class_has_section::create([
             'class_id' => $request['class_id'],
             'shift_id' => $request['shift_id'],
             'section_id' => $request['section_id'],
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
        $class_has_sections = Class_has_section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.mainentry.class_has_section.show', compact('class_has_sections'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

       $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
       $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
       $class_has_sections = Class_has_section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
       return view('backend.mainentry.class_has_section.edit', compact('class_has_sections','shifts','sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Class_has_section $c_has_section)
    {
        $request['updated_by'] = Auth::user()->id;
        if($c_has_section->update($request->all())){
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
        return redirect()->route('admin.class_has_section', $c_has_section->getClass->slug)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Class_has_section $c_has_section)
    {
        if($c_has_section->delete()){
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

    public function isactive(Request $request,$id)
    {
        $get_is_active = Class_has_section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = Class_has_section::find($id);
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
