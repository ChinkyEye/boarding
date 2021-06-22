<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use Illuminate\Database\Eloquent\Builder;
use App\Event;
use App\Shift;
use App\Section;
use App\SClass;
use Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        $events = Event::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('eng_start_date', '<=' ,date('Y-m-d'))
                        ->where('eng_end_date', '>=' ,date('Y-m-d'))
                        ->get();
        return view('backend.event.index', compact('events'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.event.create');
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
            'start_date' => 'required',
            'color' => 'required',
        ]);

        $notices= Event::create([
            'title' => $request['title'],
            'slug' => $this->helper->slug_converter($request['title']).'-'.rand(1000,9999),
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            'eng_start_date' => $this->helper->date_eng_con_parm($request['start_date']),
            'eng_end_date' => $this->helper->date_eng_con_parm($request['end_date']),
            'color' => $request['color'],
            'start_time' => $request['start_time'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.event.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $events = Event::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.event.show', compact('events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $events = Event::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.event.edit', compact('events'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $request['updated_by'] = Auth::user()->id;
        if($event->update($request->all())){
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
        return redirect()->route('admin.event.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        if($event->delete())
        {
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

    public function getEventList(Request $request){
        $data = Event::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id', 'DESC');

        if(!empty($request->date_data))
        {            
          $data = $data->where('eng_start_date', '<=' ,$request->date_data)->where('eng_end_date', '>=' ,$request->date_data);
        }

        $data = $data->get();

        $data_count = count($data);
        return view('backend.event.index-ajax', compact('data','data_count'));
    }
}
