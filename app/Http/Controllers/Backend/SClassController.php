<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Helper;
use App\SClass;
use App\Class_has_shift;
use App\Class_has_section;
use App\SubjectHasTheoryPractical;
use App\Setting;
use Riskihajar\Terbilang\Facades\Terbilang;
use Auth;
use Response;

class SClassController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $classes = SClass::where('school_id',Auth::user()->school_id)->orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(10);
        return view('backend.mainentry.class.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mainentry.class.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      if (is_numeric($request->name)){
        $name = Terbilang::make($request->name);
        $namecount =  $request->name;
        $classslugname = $name;
      }
      
      else{
        $name = strtolower($request->name);
        $namecount =  $this->helper->word_digit($name);
        $classslugname = $request->name;

      }

      $request['slug'] = $this->helper->slug_converter($name).'-'.Auth::user()->school_id;
      $this->validate($request, [
        'name' => 'required',
        'slug' => 'required|unique:s_classes|min:2',
      ]);
      
      $runningschool = Setting::where('id', Auth::user()->school_id)->value('running_class');
      

      if( $namecount > $runningschool ) {
        $pass = array(
          'message' => 'You Cannot Asssign More Class .Your Class runs Upto!'.' '.$runningschool,
          'alert-type' => 'error'
        );
        return back()->with($pass);
                // dd('okk');
      }
      
      else {
        $classes= SClass::create([
          'name' => $name,
          'slug' => $this->helper->slug_converter($classslugname).'-'.Auth::user()->school_id,
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
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->get();
        return view('backend.mainentry.class.show', compact('classes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classes = SClass::where('school_id',Auth::user()->school_id)->where('id',$id)->get();
        return view('backend.mainentry.class.edit', compact('classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SClass $class)
    {
        $check_dub = SClass::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
        if($check_dub == 0 || $request->name == $class->name){
          $this->validate($request, [
              'name' => 'required|min:2',
          ]);
          $main_data = $request->all();
          $main_data['updated_by'] = Auth::user()->id;
          if($class->update($main_data)){
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
              'name' => 'required|unique:s_classes|min:2',
          ]);
        }
        return redirect()->route('admin.class.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $classes = SClass::find($id);
            $class_shift = $classes->getCountShift->each;
            $class_section = $classes->getCountSection->each;
            $class_subject = $classes->getClassSubject->each;
            // $subject_th_pr = $class_subject->getClassSubjectPPOne->each->get();
            
            // dd($classes , $class_shift , $class_section , $class_subject , $subject_th_pr);
            // dd($subject_th_pr);

            if($class_subject){
                foreach ($class_subject->get() as $key => $value) {
                    $value->getClassSubjectPP->each->delete();
                }
                $class_subject->delete();
                $notification = array(
                                        'message' => 'Subject of '.$classes->name.' has delete successfully!',
                                        'status' => 'success'
                                    );
            }else{
                $notification = array(
                  'message' => 'Subject of '.$classes->name.' could not be delete!',
                  'status' => 'error'
                );
                return back()->with($notification);
            }

            if($class_section->delete()){
                $notification = array(
                  'message' => 'Section of '.$classes->name.' has delete successfully!',
                  'status' => 'success'
                );
            }else{
                $notification = array(
                  'message' => 'Section of '.$classes->name.' could not be delete!',
                  'status' => 'error'
                );
                return back()->with($notification);
            }

            if($class_shift->delete()){
                $notification = array(
                  'message' => 'Shifts of '.$classes->name.' has delete successfully!',
                  'status' => 'success'
                );
            }else{
                $notification = array(
                  'message' => 'Shifts of '.$classes->name.' could not be delete!',
                  'status' => 'error'
                );
                return back()->with($notification);
            }
            if($classes->delete()){
                $notification = array(
                  'message' => 'Class has delete successfully!',
                  'status' => 'success'
              );
            }else{
                $notification = array(
                  'message' => 'Class could not be delete!',
                  'status' => 'error'
                );
            }
        }
        catch (Throwable $e){
            $notification = array(
                                  'message' => 'Class could not be delete! Please refresh your page once.',
                                  'status' => 'error'
                                );
        }
        return back()->with($notification);
    }

    public function isactive(Request $request,$id)
    {
        $get_is_active = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
        $isactive = SClass::find($id);
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

    public function isSort(Request $request,$id)
    {
        $sort_ids =  SClass::find($request->id);
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
}
