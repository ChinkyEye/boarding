<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use App\Notice;
use App\Notice_for;
use App\Shift;
use App\Section;
use App\SClass;
use Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->get();
        
        $notices = Notice::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        return view('backend.notice.index', compact('shifts','notices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active',true)->get();
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active',true)->get();
        $sections = Section::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active',true)->get();
        return view('backend.notice.create', compact('shifts','classes','sections'));
    }

    public function getNoticeShift(Request $request)
    {
        $shifts = Shift::where('is_active', true)->get();
        return view('backend.notice.ajax.shift', compact('shifts'));
    } 

    public function getSaveButtom(Request $request)
    {
        return view('backend.notice.ajax.save');
    }

    public function getNoticeClassList(Request $request)
    {
        if (!empty($request->selected)) {
            # code...
            foreach ($request->selected as $key => $value) {
                $classes = SClass::whereHas('getClassList', function (Builder $query) use ($value) {
                                  $query->where('shift_id', $value);
                              })->get();
                $datas[] = $classes;
            }
        }else{
            $datas[] = [];
        }
        // dd($datas);
        return view('backend.notice.ajax.class', compact('datas'));
    }

    public function getNoticeSectionList(Request $request)
    {
        // dd($request);
        if ($request->selected) {
            foreach ($request->shift_id as $key => $shift_id) {
                foreach ($request->selected as $key => $data_id) {
                    $section_list_data = Section::whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
                                            $query->where('class_id', $data_id)->where('shift_id', $shift_id);
                                        })
                                      ->get();
                    $datas[] = $section_list_data;
                }
            }
            // $datas = collect($datas);
            // dd($datas);
        }else{
            $datas[] = [];
        }
        // dd($datas);
        return view('backend.notice.ajax.section', compact('datas'));
    }

    /**
     * @param  Request
     * @return [type]
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start_date_np' => 'required',
            'end_date_np' => 'required',
            
        ]);
        // dd($request);

        $notices= Notice::create([
            'title' => $request['title'],
            'slug' => $this->helper->slug_converter($request['title']).'-'.rand(1000,9999),
            'start_date_np' => $request['start_date_np'],
            'end_date_np' => $request['end_date_np'],
            'start_date' => $this->helper->date_eng_con_parm($request['start_date_np']),
            'end_date' => $this->helper->date_eng_con_parm($request['end_date_np']),
            'description' => $request['description'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);



        // $shift_id = $request->input('shift_id');
        // dd($shift_id);
        // foreach ($shift_id AS $shift) {
        //     $notice_for = Notice_for::create([
        //         'notice_id' => $notices->id,
        //         'shift_id' =>$shift,
        //         'school_id' => Auth::user()->school_id,
// 'batch_id' => Auth::user()->batch_id,
        //         'created_by' => Auth::user()->id,
        //         'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        //     ]);
        // }

        // $notice_for = Notice_for::create([
        //     'notice_id' =>$notices->id,
        //     'shift_id' => $request['shift_id'],
        //     'class_id' => $request['class_id'],
        //     'section_id' => $request['section_id'],
        //     'school_id' => Auth::user()->school_id,
// 'batch_id' => Auth::user()->batch_id,
        //     'created_by' => Auth::user()->id,
        //     'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        // ]);

        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.addnotice',$notices->slug)->with($pass);
    }

    /**
     * @param  [type]
     * @return [type]
     */
    public function show($id)
    {
        $notices = Notice::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        $notice_id = Notice::where('id', $id)->value('id');
        $notice_fors = Notice_for::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('notice_id', $notice_id)->get();
        // dd($notice_for_id);
        // $notice_fors = Notice_for::find($notice_fors);
        return view('backend.notice.show', compact('notices','notice_fors'));
    }

    /**
     * @param  [type]
     * @return [type]
     */
    public function edit($id)
    {
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->get();
        $notices = Notice::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.notice.edit', compact('notices','classes'));
    }

    /**
     * @param  Request
     * @param  Notice
     * @return [type]
     */
    public function update(Request $request, Notice $notice)
    {
        $request['updated_by'] = Auth::user()->id;
        if($notice->update($request->all())){
            $notification = array(
                'message' => 'Data updated successfully!',
                'status' => 'success'
            );
        }else{
            $notification = array(
                'message' => 'Data could not be updated!',
                'status' => 'error'
            );
        }
        return redirect()->route('admin.notice.index')->with($notification);
    }

    /**
     * @param  Notice
     * @return [type]
     */
    public function destroy(Notice $notice)
    {
        // $notice_id = Notice_for::where('notice_id',$notice->id)->value('id');
        // $delete_notice_for = Notice_for::find($notice_id);
        // // dd($notice_id);
        // if($delete_notice_for->delete() && $notice->delete())
        // {
        //     $notification = array(
        //         'message' => 'Data deleted successfully!',
        //         'status' => 'success'
        //     );
        // }else
        // {
        //     $notification = array(
        //         'message' => 'Data could not be deleted!',
        //         'status' => 'error'
        //     );
        // }
        // return back()->with($notification);

        $notice->where('created_by', Auth::user()->id);
        $notice_for_list = $notice->getNoticeList->each;
        if($notice_for_list->delete())
        {
            $notice->delete();
            $notification = array(
                'message' => 'Data deleted successfully!',
                'status' => 'success'
            );
        }else
        {
            $notification = array(
                'message' => 'Data could not be deleted!',
                'status' => 'error'
            );
        }
        return back()->with($notification);
    }

    public function getNoticeList(Request $request)
    {
        $data = Notice::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id', 'DESC');

        if(!empty($request->class_data))
        {            
          $data = $data->whereHas('getNoticeList', function( $query ) use ( $request ){
                                    $query->where('class_id', $request->class_data)
                                    ->where('is_active', true);
                                });
        }

        if(!empty($request->date_data))
        {            
          $data = $data->where('start_date_np', '<=' ,$request->date_data)->where('end_date_np', '>=' ,$request->date_data);
        }

        $data = $data->get();

        $data_count = count($data);
        return view('backend.notice.index-ajax', compact('data','data_count'));
    }
}
