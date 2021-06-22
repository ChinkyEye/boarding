<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Validator;
use Response;
use App\Shift;
use App\SClass;
use App\Section;
use App\Subject;
use App\Teacher;
use App\Teacher_has_shift;
use App\Teacher_has_period;
use App\Routine;
use App\Remainder;
use Auth;

class RemainderController extends Controller
{

    public function index()
    {
        $shifts = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->groupBy('shift_id')
                                    ->get();
        $remainders = Remainder::where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->where('created_by', Auth::user()->id)
                                ->whereDate('created_at', date('Y-m-d'))
                                ->orderBy('id','DESC')
                                ->get();
                                // dd($remainders);
        return view('teacher.remainder.index',compact('remainders','shifts'));
    }

    public function create()
    {
        $shifts = Teacher_has_period::where('teacher_id', Auth::user()->id)
                                    ->where('school_id', Auth::user()->school_id)
                                    ->where('batch_id', Auth::user()->batch_id)
                                    ->where('is_active', True)
                                    ->groupBy('shift_id')
                                    ->get();
        return view('teacher.remainder.create', compact('shifts'));
    }

    public function store(Request $request)
    {
         $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
        ]);

        $remainder = Remainder::create([
            'class_id' => $request['class_id'],
            'section_id' => $request['section_id'],
            'shift_id' => $request['shift_id'],
            'title' => $request['title'],
            'description' => $request['description'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('teacher.remainder.index')->with($pass);
    }

    public function show($id)
    {
        $remainders = Remainder::where('id', $id)
                                ->where('created_by', Auth::user()->id)
                                ->where('school_id', Auth::user()->school_id)
                                ->where('batch_id', Auth::user()->batch_id)
                                ->get();
        return view('teacher.remainder.show', compact('remainders'));
    }

    public function edit($id)
    {
        // not used
        $shift_id = Remainder::where('id',$id)
                            ->value('shift_id');
        $user_check = Auth::user()->id;
        $teacher_id = Teacher::where('user_id', $user_check)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->value('id');
        $teacher_shift_id = Teacher_has_shift::where('teacher_id', $teacher_id)->value('shift_id');
        $shifts = Shift::where('id',$teacher_shift_id)
                        ->where('school_id', Auth::user()->school_id)
                        ->where('batch_id', Auth::user()->batch_id)
                        ->get();

        $classes = SClass::whereHas('getRoutineClassList', function (Builder $query) use ($teacher_shift_id) {    //function change
                              $query->where('shift_id', $teacher_shift_id)
                                  ->where('user_id', Auth::user()->id);
                          })->orwhereHas('getPeriodClassList', function (Builder $query) use ($teacher_shift_id) {    //function change
                              $query->where('shift_id', $teacher_shift_id)
                                  ->where('teacher_id', Auth::user()->id);
                          })
                        ->get();
        $class_id = Remainder::where('id', $id)->value('class_id');
        $sections = Section::whereHas('getSectionList', function (Builder $query) use ($class_id,$shift_id) {
                              $query->where('class_id', $class_id)
                                  ->where('shift_id', $shift_id);
                          })
                        ->get();
        $remainders = Remainder::where('id', $id)->get();
        return view('teacher.remainder.edit', compact('remainders','shifts','classes','sections'));
    }

    public function update(Request $request, Remainder $remainder)
    {
        $request['updated_by'] = Auth::user()->id;
        if($remainder->update($request->all())){
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
        return redirect()->route('teacher.remainder.index')->with($notification);
    }

    public function destroy(Remainder $remainder)
    {
        if($remainder->where('created_by', Auth::user()->id)->delete())
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

    public function getRemainderList(Request $request){
        $remainder = Remainder::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->where('created_by', Auth::user()->id)
                            ->orderBy('id', 'DESC');
        if(!empty($request->shift_data))
        {            
          $remainder = $remainder->where('shift_id', $request->shift_data);
        }

        if(!empty($request->class_data))
        {            
          $remainder = $remainder->where('class_id', $request->class_data);
        }

        if(!empty($request->section_data))
        {            
          $remainder = $remainder->where('section_id', $request->section_data);
        }

        if(!empty($request->date_data))
        {     
        // dd($request->date_data);       
          $remainder = $remainder->whereDate('created_at_np', $request->date_data);
          // dd($remainder->get());
        }

        $remainder = $remainder->get();

        $remainders_count = count($remainder);
        return view('teacher.remainder.index-ajax', compact('remainder','remainders_count'));
    }
}
