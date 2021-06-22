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
use App\Topic;
use App\SClass;
use App\Bill;
use Response;
use Illuminate\Support\Facades\DB;


class TopicController extends Controller
{
    public function index()
    {
      return view('backend.accountsection.topic.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active',True)->get();
      return view('backend.accountsection.topic.create',compact('classes'));
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
        'topic' => 'required',
        'fee' => 'required|numeric',
        'class_id' => 'required',
      ]);
      $class_id = $request->get('class_id');
      $topic = $request->get('topic');
      $data = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('class_id','=', $class_id)->where('topic','=', $topic)->count();
      if($data > 0)
      {
        $pass = array(
          'message' => $topic.' already taken!',
          'alert-type' => 'error'
        );
      }
      else
      {
        $topics= Topic::create([
          'topic' => $request['topic'],
          'fee' => $request['fee'],
          'class_id' => $request['class_id'],
          'slug' => $this->helper->slug_converter($request['topic']).'-'.rand(1000,9999),
          'school_id' => Auth::user()->school_id,
          'batch_id' => Auth::user()->batch_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
          'message' => 'Topic added successfully!',
          'alert-type' => 'success'
        );
      }
      return back()->with($pass);
    }

    public function getAllAccountTopic(Request $request)
    {
        $columns = array(
            0 =>'id', 
            1 =>'topic',
            2 =>'class_id',
            3 =>'fee',
            4 =>'created_by',
            5 =>'status',
            6 =>'action',
        );
        $totalData = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

       $posts = Topic::offset($start)->where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id);
        if(empty($request->input('search.value')))
        {            
            $posts = $posts;
        }
        else {
          $search = $request->input('search.value'); 
          $posts = Topic::offset($start)
                        ->where('topic', 'LIKE',"%{$search}%")
                        ->orWhere('fee', 'LIKE',"%{$search}%")
                        ->orwhereHas('getClass', function (Builder $query) use ($search) {
                            $query->where('name', 'LIKE',"%{$search}%");
                        });
          $totalFiltered = Topic::where('school_id',Auth::user()->school_id)
                                ->where('topic', 'LIKE',"%{$search}%")
                                ->orWhere('fee', 'LIKE',"%{$search}%")
                                ->orwhereHas('getClass', function (Builder $query) use ($search) {
                                  $query->where('name', 'LIKE',"%{$search}%");
                                })
                                ->count();
        }
        $posts = $posts->offset($start)
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
                $nestedData['class_id'] = $post->getClass->name;
                $nestedData['topic'] = $post->topic;
                $nestedData['fee'] = $post->fee;
                $nestedData['created_by'] = "<div class='text-center'>".$post->getUser->name."</div>";
                $nestedData['status'] = "
                <a class='d-block text-center' href='".route('admin.topic.active',$post->id)."' data-toggle='tooltip' data-placement='top' title='".$attribute_title."'>
                <i class='fa ".$class_icon."'></i>
                </a>
                ";
                $nestedData['action'] = "
                <div class='text-center'>
                
                <a href='".route('admin.topic.edit',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 

                <form action='javascript:void(0)' data_url='".route('admin.topic.destroy',$post->id)."' method='post' class='d-inline-block' data-toggle='tooltip' data-placement='top' title='Permanent Delete' onclick='myFunction(this)'>
                <input type='hidden' name='_token' value='".csrf_token()."'>
                <input name='_method' type='hidden' value='DELETE'>
                <button class='btn btn-xs btn-outline-danger' type='submit'><i class='fa fa-trash'></i></button>
                </form>
                </div>
                ";
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topics = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.accountsection.topic.show', compact('topics'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active', True)->get();
        $topics = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.accountsection.topic.edit', compact('topics','classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Topic $topic)
    {
      $this->validate($request, [
        'topic' => 'required',
        'fee' => 'required|numeric',
        'class_id' => 'required',
      ]);
      $request['updated_by'] = Auth::user()->id;
      if($topic->update($request->all())){
        $notification = array(
          'message' => $request['topic'].' updated successfully!',
          'alert-type' => 'success'
        );
      }else{
        $notification = array(
          'message' => $topic->topic.' could not be updated!',
          'alert-type' => 'error'
        );
      }
      return redirect()->route('admin.topic.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
      try{
        return DB::transaction(function() use ($topic)
        {
         $data_id = $topic->id;
         $bill_id = Bill::where('topic_id', $data_id)->value('id');
         $delete_bill = Bill::find($bill_id);

         if($delete_bill){
          $delete_bill->delete();
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
        if($topic->delete()){
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
      }catch(\Exception $e){
        DB::rollback();
        throw $e;
    // dd($e);
    // abort(404);
      }
      DB::commit();

    }

    public function isactive(Request $request,$id)
    {
      $get_is_active = Topic::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('is_active');
      $isactive = Topic::find($id);
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
