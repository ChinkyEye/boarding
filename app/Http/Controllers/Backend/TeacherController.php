<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use Validator;
use Response;
use App\Teacher;
use App\Teacher_has_shift;
use App\Teacher_has_subject;
use App\Teacher_has_period;
use App\Staff_has_bank;
use App\Shift;
use App\SClass;
use App\Section;
use App\Nationality;
use App\Routine;
use App\Homework;
use File;
use App\User;
use App\TeacherIncome;
use App\Teacher_has_attendance;
use App\UserHasBatch;
use Illuminate\Support\Facades\Hash;
use App\Exports\TeachersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
   

    public function export(Request $request)
    {
      // dd($request);
      $excShift = $request->excShift;
      $excClass = $request->excClass;
      $excSection = $request->excSection;
      $date = $this->helper->date_np_con_parm(date('Y-m-d'));
      return Excel::download(new TeachersExport($excShift,$excClass,$excSection), $date.'Teacher.xlsx');
    }

    public function index(Request $request)
    {
      $common_batch =$this->batchCheck($request)['batch'];
      $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      return view('backend.primaryentry.teacher.index',compact('shifts','common_batch'));
    }


    public function getAllTeacher(Request $request)
    {
      $columns = array(
        0 =>'id', 
        1 =>'f_name',
        2 =>'email',
        3 =>'phone',
        4 =>'created_by',
        5 =>'status',
        6 =>'action',
                        // 8 =>'count',
      );
      $batch_data = $request->data['batch_data'];
      // $totalData = Teacher::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','desc')->count();
      $totalData = UserHasBatch::whereHas('getTeacherBatch', function(Builder $query){
        $query->where('school_id', Auth::user()->school_id)
        ->where('is_active','1')
        ->orderBy('id','desc');
      })->where('batch_id',$batch_data)
      ->count();
      $totalFiltered = $totalData; 
      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');
      $shift_data = $request->data['shift_data'];
      $class_data = $request->data['class_data'];
      $section_data = $request->data['section_data'];
      $batch_data = $request->data['batch_data'];

      if(empty($request->input('search.value')))
      {       
        // $posts = Teacher::offset($start);
        $posts = UserHasBatch::whereHas('getTeacherBatch', function(Builder $query){
          $query->where('batch_id', Auth::user()->batch_id)
          ->where('school_id', Auth::user()->school_id)
          ->where('is_active','1')
          ->orderBy('id','desc');
        })->where('batch_id', Auth::user()->batch_id)
        ->offset($start);

        if(!empty($request->data['batch_data'])){
          $posts = UserHasBatch::whereHas('getTeacherBatch', function(Builder $query){
            $query->where('school_id', Auth::user()->school_id)
            ->where('is_active','1')
            ->orderBy('id','desc');
          })->where('batch_id',$batch_data)
          ->offset($start);
        }
        $teachershifts = Teacher_has_shift::offset($start);
        if(!empty($request->data['shift_data'])){
          $shift_id = Shift::value('id');
          $posts = $posts->whereHas('getTeacherUserBatchShift', function (Builder $query) use ($shift_data) {
            $query->where('shift_id', $shift_data);
          });
        }
        if(!empty($request->data['class_data'])){
          $posts = $posts->whereHas('getTeacherUserBatchPeriod', function (Builder $query) use ($class_data) {
            $query->where('class_id', $class_data);
          });
        }
        if(!empty($request->data['section_data'])){
          $posts = $posts->whereHas('getTeacherUserBatchPeriod', function (Builder $query) use ($section_data) {
            $query->where('section_id', $section_data);
          });
        }
      }

      else {
        $search = $request->input('search.value'); 

        // $posts = UserHasBatch::offset($start)
        //                ->whereHas('getTeacherUserBatch', function($query) use ($search){
        //                   $query->where('name', 'LIKE', '%'.$search.'%')
        //                         ->orWhere('middle_name','LIKE', '%'.$search.'%')
        //                         ->orWhere('last_name','LIKE', '%'.$search.'%')
        //                         ->orWhere('email','LIKE', '%'.$search.'%');
        //                });
        $posts = UserHasBatch::offset($start)
        ->whereHas('getTeacherUserBatch', function($query) use ($search){
          $query->where('name', 'LIKE', '%'.$search.'%')
          ->orWhere('middle_name','LIKE', '%'.$search.'%')
          ->orWhere('last_name','LIKE', '%'.$search.'%');
        });
        
        if(!empty($request->data['batch_data'])){
          $posts = UserHasBatch::whereHas('getTeacherBatch', function(Builder $query){
            $query->where('school_id', Auth::user()->school_id)
            ->where('is_active','1')
            ->orderBy('id','desc');
          })->where('batch_id',$batch_data)
          ->offset($start);
        }
        if(!empty($request->data['shift_data'])){
          $posts = $posts->whereHas('getTeacherUserBatchShift', function (Builder $query) use ($shift_data) {
            $query->where('shift_id', $shift_data);
          });
        }
        if(!empty($request->data['class_data'])){
          $posts = $posts->whereHas('getTeacherUserBatchPeriod', function (Builder $query) use ($class_data) {
            $query->where('class_id', $class_data);
          });
        }
        if(!empty($request->data['section_data'])){
          $posts = $posts->whereHas('getTeacherUserBatchPeriod', function (Builder $query) use ($section_data) {
           $query->where('section_id', $section_data);
         });
        }
        $totalFiltered = UserHasBatch::where('is_active','1')
                                // ->where('f_name', 'LIKE',"%{$search}%")
                                // ->orWhere('m_name', 'LIKE',"%{$search}%")
                                // ->orWhere('l_name', 'LIKE',"%{$search}%")
        ->count();
      }

      $posts = $posts->whereHas('getTeacherBatch', function(Builder $query){
        $query->where('school_id', Auth::user()->school_id)->where('is_active','1')->orderBy('id','desc');
      })->limit($limit)
      ->orderBy($order,$dir)
      ->get();
                    // $posts = $posts->with('getTeacherUser')->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    // ->limit($limit)
                    // ->orderBy($order,$dir)
                    // ->get();
      
      $data = array();
      if(!empty($posts))
      {
        foreach ($posts as $index=>$post)
        {
          if($post->is_active == '1') 
          { 
            $attribute_title = 'Click to deactivate'; 
            $class_icon = 'fa-check check-css'; 
          }
          else{ 
            $attribute_title = 'Click to activate'; 
            $class_icon = 'fa-times cross-css'; 
          }
          $nestedData['id'] = $index+1;
          $nestedData['f_name'] = $post->getTeacherUserBatch->name." ".$post->getTeacherUserBatch->middle_name." ".$post->getTeacherUserBatch->last_name." ";
          $nestedData['email'] = $post->getTeacherUserBatch->email;
          $nestedData['phone'] = $post->getTeacherBatch->phone;
          $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
          $nestedData['status'] = "
          <a class='d-block text-center' href='".route('admin.teacher.active',$post->getTeacherBatch->slug)."' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
          <i class='fa ".$class_icon."'></i>
          </a>
          ";
          $nestedData['action'] = "
          <div class='text-center'>
          <a  href='".route('admin.staffhasbank',$post->getTeacherBatch->slug)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Add Bank'>".$post->getTeacherBatch->getBank()->count()."<i class='fa fa-university'></i></a>

          <a  href='".route('admin.teacherhasperiod',$post->getTeacherBatch->slug)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Add Class'>".$post->getTeacherBatch->getTeacherPeriod()->count()."<i class='fa fa-plus'></i></a>

          <a href='".route('admin.teacher.show',$post->getTeacherBatch->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
          <a href='".route('admin.teacher.edit',$post->getTeacherBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 
          
          <form action='javascript:void(0)' data_url='".route('admin.teacher.destroy',$post->getTeacherBatch->id)."' method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete' onclick='myFunction(this)'>
          <input type='hidden' name='_token' value='".csrf_token()."'>
          <input name='_method' type='hidden' value='DELETE'>
          <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
          </form>
          <a href='".route('admin.teacher.resetPassword',$post->getTeacherBatch->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='Reset Password'><i class='fa fa-key'></i></a>
          <a href='".route('admin.teacher.cardPrint',$post->getTeacherBatch->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Print Card'><i class='fa fa-print'></i></a>
          </div>
          ";
           // $nestedData['count'] = $post->getClassCount->count();
          $data[] = $nestedData;
        }
      }
      $json_data = array(
        "draw"            => intval($request->input('draw')),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalFiltered), 
        "data"            => $data   
      );
      echo json_encode($json_data); 
    } 
    
    public function getAllTeacher2(Request $request)
    {
      $columns = array(
        0 =>'id', 
        1 =>'f_name',
        2 =>'email',
        3 =>'phone',
        4 =>'created_by',
        5 =>'status',
        6 =>'action',
        // 8 =>'count',
      );
      $totalData = Teacher::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->orderBy('id','desc')->count();
      $totalFiltered = $totalData; 
      $limit = $request->input('length');
      $start = $request->input('start');
      $order = $columns[$request->input('order.0.column')];
      $dir = $request->input('order.0.dir');
      $shift_data = $request->data['shift_data'];
      $class_data = $request->data['class_data'];
      $section_data = $request->data['section_data'];

      if(empty($request->input('search.value')))
      {       
        $posts = Teacher::offset($start);
        $teachershifts = Teacher_has_shift::offset($start);
        if(!empty($request->data['shift_data'])){
          $shift_id = Shift::value('id');
          $posts = $posts->whereHas('getShiftTeacherManyList', function (Builder $query) use ($shift_data) {
                          $query->where('shift_id', $shift_data);
                        });
        }
        if(!empty($request->data['class_data'])){
          $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($class_data) {
                          $query->where('class_id', $class_data);
                        });
        }
        if(!empty($request->data['section_data'])){
          $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($section_data) {
                          $query->where('section_id', $section_data);
                        });
        }
      }

      else {
        $search = $request->input('search.value'); 

        $posts = Teacher::offset($start)
                       ->whereHas('getTeacherUser', function($query) use ($search){
                          $query->where('name', 'LIKE', '%'.$search.'%')
                                ->orWhere('middle_name','LIKE', '%'.$search.'%')
                                ->orWhere('last_name','LIKE', '%'.$search.'%')
                                ->orWhere('email','LIKE', '%'.$search.'%');
                       });
                       // ->orWhere('phone', 'LIKE',"%{$search}%")
                       // ->orWhere('teacher_code', 'LIKE',"%{$search}%");                
                        
        if(!empty($request->data['shift_data'])){
          $posts = $posts->whereHas('getShiftTeacherList', function (Builder $query) use ($shift_data) {
                      $query->where('shift_id', $shift_data);
                    });
        }
        if(!empty($request->data['class_data'])){
          $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($class_data) {
                            $query->where('class_id', $class_data);
                          });
        }
        if(!empty($request->data['section_data'])){
          $posts = $posts->whereHas('getTeacherPeriod', function (Builder $query) use ($section_data) {
                           $query->where('section_id', $section_data);
                         });
        }
        $totalFiltered = Teacher::where('is_active','1')
                                // ->where('f_name', 'LIKE',"%{$search}%")
                                // ->orWhere('m_name', 'LIKE',"%{$search}%")
                                // ->orWhere('l_name', 'LIKE',"%{$search}%")
                                ->count();
      }

      $posts = $posts->with('getTeacherUser')->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
     
      $data = array();
      if(!empty($posts))
      {
        foreach ($posts as $index=>$post)
        {
          if($post->is_active == '1') 
          { 
            $attribute_title = 'Click to deactivate'; 
            $class_icon = 'fa-check check-css'; 
          }
          else{ 
            $attribute_title = 'Click to activate'; 
            $class_icon = 'fa-times cross-css'; 
          }
          $nestedData['id'] = $index+1;
          $nestedData['f_name'] = $post->getTeacherUser->name." ".$post->getTeacherUser->middle_name." ".$post->getTeacherUser->last_name." "."(".$post->teacher_code.")";
          $nestedData['email'] = $post->getTeacherUser->email;
          $nestedData['phone'] = $post->phone;
          $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
          $nestedData['status'] = "
            <a class='d-block text-center' href='".route('admin.teacher.active',$post->slug)."' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
              <i class='fa ".$class_icon."'></i>
            </a>
          ";
          $nestedData['action'] = "
          <div class='text-center'>
            <a  href='".route('admin.staffhasbank',$post->slug)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Add Bank'>".$post->getBank()->count()."<i class='fa fa-university'></i></a>

            <a  href='".route('admin.teacherhasperiod',$post->slug)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Add Class'>".$post->getTeacherPeriod()->count()."<i class='fa fa-plus'></i></a>

            <a href='".route('admin.teacher.show',$post->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
            <a href='".route('admin.teacher.edit',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 
          
            <form action='javascript:void(0)' data_url='".route('admin.teacher.destroy',$post->id)."' method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete' onclick='myFunction(this)'>
              <input type='hidden' name='_token' value='".csrf_token()."'>
              <input name='_method' type='hidden' value='DELETE'>
              <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
            </form>
            <a href='".route('admin.teacher.resetPassword',$post->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='Reset Password'><i class='fa fa-key'></i></a>
            <a href='".route('admin.teacher.cardPrint',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top' title='Print Card'><i class='fa fa-print'></i></a>
          </div>
          ";
           // $nestedData['count'] = $post->getClassCount->count();
          $data[] = $nestedData;
        }
      }
      $json_data = array(
        "draw"            => intval($request->input('draw')),  
        "recordsTotal"    => intval($totalData),  
        "recordsFiltered" => intval($totalFiltered), 
        "data"            => $data   
      );
      echo json_encode($json_data); 
    } 
    public function cardPrint(Request $request)
    {
      // dd($request->id);
      $teacher_info = Teacher::where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->where('is_active', True)
                            ->find($request->id);
      return view('backend.primaryentry.teacher.card',compact('teacher_info'));
    }

    public function resetPassword(Request $request)
    {
      // dd($request);
      $teacher = Teacher::find($request->id);
      $user_id = User::find($teacher->user_id);
      // dd($student,$user_id);
      $password = 'admin123';
      $user_id->password = Hash::make($password);
      $user_id->reset_time = $password;
      // $user_id->reset_time = $this->helper->date_np_con()." ".date("H:i:s");

      $user_id->save();
      return back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      $nationalities  = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
      return view('backend.primaryentry.teacher.create',compact('shifts','nationalities'));
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
            'f_name' => 'required',
            'l_name' => 'required',
            'designation' => 'required',
            'teacher_code' => 'required',
            'uppertype' => 'required',
            't_designation' => 'required',
            'training' => 'required',
            'qualification' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'nationality_id' => 'required',
            'marital_status' => 'required',
            'j_date' => 'required',
            'image' => 'required|mimes:jpeg,jpg|max:1024',
            'cinvestment_id' => 'required',
            'shift_id' => 'required',
        ]);
       $request['slug'] = $this->helper->slug_converter($request->f_name).'-'.rand(1000,9999);
       $uppdf = $request->file('image');
       if($uppdf != ""){
         $destinationPath = 'images/teacher/'.$request->slug;
         $extension = $uppdf->getClientOriginalExtension();
         $fileName = md5(mt_rand()).'.'.$extension;
         $uppdf->move($destinationPath, $fileName);
         $file_path = $destinationPath.'/'.$fileName;
       }
       $user= User::create([
          'name' => $request['f_name'],
          'middle_name' => $request['m_name'],
          'last_name' => $request['l_name'],
          'email' => $request['email'],
          'phone_no' => $request['phone_no'],
          'password' => Hash::make($request['password']),
          'user_type' => '3',
          'is_active' => '1',
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
       ]);
       $teacher = Teacher::create([
          'user_id' => $user->id,
          'slug' => $request['slug'],
          'designation' => $request['designation'],
          'teacher_code' => $request['teacher_code'],
          'training' => $request['training'],
          'qualification' => $request['qualification'],
          'dob' => $request['dob'],
          'phone' => $request['phone'],
          'address' => $request['address'],
          'gender' => $request['gender'],
          'religion' => $request['religion'],
          'nationality_id' => $request['nationality_id'],
          'marital_status' => $request['marital_status'],
          'uppertype' => $request['uppertype'],
          't_designation' => $request['t_designation'],
          'j_date' => $request['j_date'],
          'p_date' => $request['p_date'],
          'image' => $fileName,
          'government_id' => $request['government_id'],
          'insurance_id' => $request['insurance_id'],
          'pan_id' => $request['pan_id'],
          'cinvestment_id' => $request['cinvestment_id'],
          'pfund_id' => $request['pfund_id'],
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
       ]);
       $shift_id = $request->input('shift_id');
        if($teacher->wasRecentlyCreated){
          foreach( $shift_id AS $shift ){
            $teachershift= Teacher_has_shift::create([
              'user_id' => $user->id,
              'teacher_id' => $teacher->id,
              'shift_id' => $shift,
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
            ]);
          }
        }
       else{
         $delete_user = User::where('id', $user->id)->delete();
       }
       $user_has_batches = UserHasBatch::create([
        'user_id' => $user->id,
        'batch_id' => $teacher->batch_id,
        'created_by' => Auth::user()->id,
        'created_at_np' => date("Y-m-d")." ".date("H:i:s"),

      ]);
      
       $pass = array(
         'message' => 'Data added successfully Please enter other details of teacher!',
         'alert-type' => 'success'
       );
       return redirect()->route('admin.teacherhasperiod',['slug' => $teacher->slug])->with($pass)->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher_id = Teacher::where('id', $id)->value('user_id');
        $teachers = User::find($teacher_id)->getUserTeacher()->get();
        return view('backend.primaryentry.teacher.show', compact('teachers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teachers = Teacher::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        $teacherhasshifts = Teacher_has_shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('teacher_id',$id)->get();
        $nationalities  = Nationality::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        // dd($teacherhasshifts);
        return view('backend.primaryentry.teacher.edit', compact('teachers','shifts','teacherhasshifts','nationalities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Teacher $teacher)
    {
        $this->validate($request, [
            'f_name' => 'required',
            'l_name' => 'required',
            'designation' => 'required',
            'teacher_code' => 'required',
            'uppertype' => 'required',
            't_designation' => 'required',
            'training' => 'required',
            'qualification' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'religion' => 'required',
            'nationality_id' => 'required',
            'marital_status' => 'required',
            'j_date' => 'required',
            'cinvestment_id' => 'required',
            'shift_id' => 'required',
        ]);

        $uppdf = $request->file('image');
        $all_data = $request->all();
        if($uppdf != ""){
            $this->validate($request, [
              'image' => 'required|mimes:jpeg,jpg|max:1024',
            ]);
            $destinationPath = 'images/teacher/'.$teacher->slug;
            $oldFilename = $destinationPath.$teacher->image;
            $extension = $uppdf->getClientOriginalExtension();
            $fileName = md5(mt_rand()).'.'.$extension;
            $uppdf->move($destinationPath, $fileName);
            $file_path = $destinationPath.'/'.$fileName;
            $all_data['image'] = $fileName;
            if(File::exists($oldFilename)) {
                File::delete($oldFilename);
            }
        }
        $all_data['updated_by'] = Auth::user()->id;
        $teach_shift_id = Teacher_has_shift::where('teacher_id',$teacher->id)->value('id');
        $user = Teacher_has_shift::where('teacher_id',$teacher->id)->value('user_id');
        if($teacher->update($all_data)){
          $user_update= User::find($teacher->user_id);
          $user_update->name = $request['f_name'];
          $user_update->middle_name = $request['m_name'];
          $user_update->last_name = $request['l_name'];
          $user_update->update();
          $shift_id = $request->input('shift_id');
          $request_shift_count = count($request->shift_id); 
          $check_data = Teacher_has_shift::where('teacher_id',$teacher->id)->count(); 
          if($request_shift_count == $check_data){
            foreach( $shift_id as $shift ){
              $teach_shift = Teacher_has_shift::find($teach_shift_id);
              $teach_shift->teacher_id = $teacher->id;
              $teach_shift->shift_id = $shift;
              $teach_shift->updated_by = Auth::user()->id;
              $teach_shift->update();
            }
          }
          if($request_shift_count ){
            Teacher_has_shift::where('teacher_id',$teacher->id)->delete();
            foreach( $shift_id as $shift ){
              $teacher_store = Teacher_has_shift::create([
                'teacher_id' => $teacher->id,
                'user_id' => $user,
                'shift_id' => $shift,
                'school_id' => Auth::user()->school_id,
                'batch_id' => Auth::user()->batch_id,
                'created_by' => Auth::user()->id,
                'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
              ]); 
            }
          }
         
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
        return redirect()->route('admin.teacher.index')->with($notification)->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
      try{
        return DB::transaction(function() use ($teacher)
        {
          $data_id = $teacher->id;

          $teachers = Teacher::find($data_id);
          $teacher_shift = $teachers->getShiftTeacherCountList->each;
          $teacher_subject = $teachers->getShiftTeacherSubjectCountList->each;
          $teacher_period = $teachers->getTeacherPeriodCount->each;
          $bank_id = Staff_has_bank::where('user_id',$teachers->user_id)->value('id');
          $teacher_bank = Staff_has_bank::find($bank_id);
        // dd($bank_id,$teacher_bank);

        // dd($teacher_subject,$teacher_period);
          $delete_user = User::find($teachers->user_id);

          $teacher_attendance_id = Teacher_has_attendance::where('teacher_id',$data_id)->value('id');
          $delete_teacher_attendance = Teacher_has_attendance::find($teacher_attendance_id);

          $routine_id = Routine::where('teacher_id',$data_id)->value('id');
          $delete_routine = Routine::find($routine_id);

          $homework_id = Homework::where('teacher_id',$delete_user->id)->value('id');
          $delete_homework = Homework::find($homework_id);

          $teacherincome_id = TeacherIncome::where('teacher_id',$data_id)->value('id');
          $delete_teacherincome = TeacherIncome::find($teacherincome_id);

          $user_has_batch_id = UserHasBatch::where('user_id',$teachers->user_id)->value('id');
          $delete_user_has_batch = UserHasBatch::find($user_has_batch_id);

          $destinationPath = 'images/teacher/';
          $oldFilename=$destinationPath.$teacher->image;

          if($delete_user_has_batch){
            $delete_user_has_batch->delete();
          }

          if($delete_routine){
            $delete_routine->delete(); //it is here becoz it has foreign key with teacher_has_subject
          }

          if($teacher_subject->delete()){
            $notification = array(
              'message' => 'Subject of '.$teachers->f_name.' has delete successfully!',
              'status' => 'success'
            );
          }else{
            $notification = array(
              'message' => 'Subject of '.$teachers->f_name.' could not be delete!',
              'status' => 'error'
            );
            return back()->with($notification);
          }

          if($teacher_period->delete()){
            $notification = array(
              'message' => 'Shifts of '.$teachers->f_name.' has delete successfully!',
              'status' => 'success'
            );
          }else{
            $notification = array(
              'message' => 'Shifts of '.$teachers->f_name.' could not be delete!',
              'status' => 'error'
            );
            return back()->with($notification);
          }

          if($teacher_shift->delete()){
            $notification = array(
              'message' => 'Shifts of '.$teachers->f_name.' has delete successfully!',
              'status' => 'success'
            );
          }else{
            $notification = array(
              'message' => 'Shifts of '.$teachers->f_name.' could not be delete!',
              'status' => 'error'
            );
            return back()->with($notification);
          }
          if($teachers){
            if($delete_teacher_attendance){
              $delete_teacher_attendance->delete();
            }
            if($delete_teacherincome){
              $delete_teacherincome->delete();
            }
            if($delete_homework){
              $delete_homework->delete();
            }
            
            if($teacher_bank){
              $teacher_bank->delete();
            }
            $teachers->delete();
            $delete_user->delete();
            if(File::exists($oldFilename)) {
              File::delete($oldFilename);
            }
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


          return back()->with($notification)->withInput();
        });
      } catch(\Exception $e){
        DB::rollback();
        throw $e;
        // dd($e);
        // abort(404);
      }
      DB::commit();
    }

    public function isSort(Request $request,$id)
    {
      $sort_ids =  Teacher::find($request->id);
      $sort_ids->sort_id = $request->value;
      if($sort_ids->save()){
        $response = array(
          'status' => 'success',
          'msg' =>$sort_ids->f_name.$sort_ids->l_name.$sort_ids->m_name.' Successfully changed position to '.$request->value,
        );
      }else{
        $response = array(
          'status' => 'failure',
          'msg' => 'Sorry, '.$sort_ids->f_name.$sort_ids->l_name.$sort_ids->m_name.' could not change position to '.$request->value,
        );
      }
      return Response::json($response);
    }

    public function isactive(Request $request,$id)
    {
      $teacher = Teacher::where('slug',$id)->value('id');
      $isactive = Teacher::find($teacher);
      $userisactive = User::find($isactive->user_id);
      $get_is_active = Teacher::where('slug',$id)->value('is_active');
      if($get_is_active == 0){
        $isactive->is_active = 1;
        $userisactive->is_active = 1;
        $notification = array(
          'message' => $isactive->f_name.$isactive->l_name.$isactive->m_name.' is Active!',
          'alert-type' => 'success'
        );
      }
      else {
        $isactive->is_active = 0;
        $userisactive->is_active = 0;
        $notification = array(
          'message' => $isactive->f_name.$isactive->l_name.$isactive->m_name.' is inactive!',
          'alert-type' => 'error'
        );
      }
      $userisactive->update();
      if(!($isactive->update())){
        $notification = array(
          'message' => $isactive->f_name.$isactive->l_name.$isactive->m_name.' could not be changed!',
          'alert-type' => 'error'
        );
      }
      return back()->with($notification)->withInput();
    }

}
