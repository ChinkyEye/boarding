<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use App\Notice;
use App\Notice_for;
use App\Shift;
use App\Section;
use App\SClass;
use App\Teacher_has_shift;
use App\Teacher_has_period;
use Auth;
use Illuminate\Support\Facades\DB;


class NoticeController extends Controller
{
    public function index()
    {
        $shifts = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->groupBy('shift_id')
                                    ->get();
        $notices = Notice::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->orderBy('id', 'DESC')
                        ->where('start_date', '<=' , Date('Y-m-d'))->where('end_date', '>=' , Date('Y-m-d'))
                        ->get();
                        // dd($notices);
        return view('teacher.notice.index', compact('shifts','notices'));
    }

    public function create()
    {
        $period_class = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    // ->groupBy('shift_id')
                                    ->get();
        return view('teacher.notice.create', compact('period_class'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start_date_np' => 'required',
            'end_date_np' => 'required',
            'class_id' => 'required',
        ]);

        $notices = Notice::create([
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
        foreach ($request->class_id as $key => $value) {
            $notice_for = Notice_for::create([
                'notice_id' =>$notices->id,
                'class_id' => $value,
                'school_id' => Auth::user()->school_id,
                'batch_id' => Auth::user()->batch_id,
                'created_by' => Auth::user()->id,
                'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            ]);
        }
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('teacher.notice.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notices = Notice::where('id', $id)
                        ->where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->get();
        return view('teacher.notice.show', compact('notices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
                        // dd($id);

        $period_class = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->get();
        $notices = Notice::where('school_id', Auth::user()->school_id) //where('created_by', Auth::user()->id) 
                        ->where('batch_id', Auth::user()->batch_id)
                        ->with('getNoticeList')
                        ->find($id);
                        // dd($notices);
        return view('teacher.notice.edit', compact(['period_class', 'notices']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
        return redirect()->route('teacher.notice.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notice $notice)
    {
        try{
            return DB::transaction(function() use ($notice){
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
            });
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        DB::commit();
    }

    public function getNoticeList(Request $request)
    {
        $data = Notice::where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->orderBy('id', 'DESC')
                        ->where('is_active', True);

        if(!empty($request->class_data))
        {            
          $data = $data->whereHas('getNoticeList', function( $query ) use ( $request ){
                                    $query->where('class_id', $request->class_data)
                                    ->where('is_active', true);
                                });
        }

        if(!empty($request->date_data))
        {            
          $data = $data->where('start_date_np', '<=' ,$request->date_data)
                      ->where('end_date_np', '>=' ,$request->date_data);
        }

        $data = $data->get();
        // dd($request->date_data);

        $data_count = count($data);
        return view('teacher.notice.index-ajax', compact('data','data_count'));
    }
}
