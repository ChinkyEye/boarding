<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Notice_for;
use App\Notice;
use App\SClass;



class NoticeForController extends Controller
{
    public function index($slug)
    {
        $notice_id = Notice::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->where('slug',$slug)->value('id');
        $noticefors = Notice_for::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->where('notice_id',$notice_id)->get();
        // dd($noticefors);
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
        return view('backend.notice.add',compact(['notice_id','classes','noticefors']));
    }

    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
       
        $notice_id = $request->notice_id;
        $class_id = $request->class_id;
        $request_class_count = count($request->class_id); 
        $check_data = Notice_for::where('notice_id',$notice_id)->count(); 
        if($request_class_count == $check_data){
          // dd('equal');
          foreach( $class_id as $class ){
            $teach_class = Notice_for::find($notice_id);
            $teach_class->notice_id = $notice_id;
            $teach_class->class_id = $class;
            $teach_shift->updated_by = Auth::user()->id;
            $teach_shift->update();
          }
        }
        if($request_class_count ){
          Notice_for::where('notice_id',$notice_id)->delete();
          foreach( $class_id as $class ){
            $notice_store = Notice_for::create([
              'notice_id' => $notice_id,
              'class_id' => $class,
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            ]); 
          }
        }

      

        $pass = array(
            'message' => 'Notice Published added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.notice.index')->with($pass);
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        // foreach ($max_count as $key => $data_list) {
        //     $notice_for = Notice_for::create([
        //         'notice_id' => $notice_id,

        //         'shift_id' => !empty($shift_id[$key]) ? $shift_id[$key] : "Null",
        //         'class_id' =>  !empty($class_id[$key]) ? $class_id[$key] : "",
        //         'section_id' =>  !empty($section_id[$key]) ? $section_id[$key] : "",

        //         'school_id' => Auth::user()->school_id,
// 'batch_id' => Auth::user()->batch_id,
        //         'created_by' => Auth::user()->id,
        //         'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        //     ]);
        // }

        // foreach ($shift_id as $key => $shift) {
        //     $notice_for = Notice_for::create([
        //         'notice_id' => $notice_id,

        //         'shift_id' => $shift ? $shift : "Null",
        //         'class_id' =>  !empty($class_id[$key]) ? $class_id[$key] : "",
        //         'section_id' =>  !empty($section_id[$key]) ? $section_id[$key] : "",

        //         'school_id' => Auth::user()->school_id,
// 'batch_id' => Auth::user()->batch_id,
        //         'created_by' => Auth::user()->id,
        //         'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        //     ]);
        // }

        // $section_id = $request->section_id;

        // $max_count = count($shift_id) >= count($class_id) ? 
        //             (count($shift_id) >= count($section_id) ? $shift_id : $section_id) : 
        //             (count($class_id) >= count($section_id) ? $class_id : $section_id);

        // dd($shift_id, $class_id, $section_id , count($shift_id) , count($class_id) , $max_count);

    }

    
    public function update(Request $request, $id)
    {
        //
    }

    
    public function destroy($id)
    {
        //
    }
}
