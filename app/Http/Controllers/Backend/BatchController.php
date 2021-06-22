<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use App\Batch;
use App\User;
use Auth;
use Response;
use App\Notifications\InvoicePaid;

class BatchController extends Controller
{

    public function index()
    {
      // dd(User::find(1)->notify(new InvoicePaid));
        $batchs=Batch::orderBy('sort_id','DESC')->orderBy('id','DESC')->paginate(5); //school sutaune code
        return view('backend.mainentry.batch.index',compact('batchs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.mainentry.batch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request['slug'] = $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id; 
      $this->validate($request, [
          'name' => 'required|min:2',
          'slug' => 'required|unique:batches|min:2',
      ]);

      $batchs = Batch::create([
          'name' => $request['name'],
          'slug' => $this->helper->slug_converter($request['name']).'-'.Auth::user()->school_id,
          'school_id' => Auth::user()->school_id,
          'created_by' => Auth::user()->id,
          'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
      ]);
      $pass = array(
        'message' => 'Data added successfully!',
        'alert-type' => 'success'
      );
      return redirect()->route('admin.batch.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $batchs = Batch::where('id', $id)->where('school_id', Auth::user()->school_id)->get();
        return view('backend.mainentry.batch.edit', compact('batchs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Batch $batch)
    {
        $check_dub = Batch::where('school_id',Auth::user()->school_id)->where('name',$request->name)->count();
        if($check_dub == 0 || $request->name == $batch->name){
          $this->validate($request, [
              'name' => 'required|min:2',
          ]);
          $main_data = $request->all();
          $main_data['updated_by'] = Auth::user()->id;
          if($batch->update($main_data)){
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
              'name' => 'required|unique:batches|min:2',
          ]);
        }
        return redirect()->route('admin.batch.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        if($batch->delete()){
          $notification = array(
            'message' => $batch->name.' is deleted successfully!',
            'status' => 'success'
          );
        }else{
          $notification = array(
            'message' => $batch->name.' could not be deleted!',
            'status' => 'error'
          );
        }
        return Response::json($notification);
    }

    public function isactive(Request $request,$id)
    {
        $get_is_active = Batch::where('school_id', Auth::user()->school_id)->where('id',$id)->value('is_active');
        $isactive = Batch::find($id);
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
