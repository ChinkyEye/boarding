<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Helper\Helper;
use App\User;
use App\Setting;
use App\UserHasBatch;
use Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    
    public function index()
    {
        // 
    }

    public function create()
    {
        //
    }
    public function main($slug)
    {
        $settings = Setting::where('created_by', Auth::user()->id)
                            ->where('user_type',1)
                            ->orderBy('id','ASC')
                            ->get();
        $school_id = Setting::where('slug',$slug)->value('id');
        $admins = User::where('school_id', $school_id)->where('user_type', 1)->get();
        $principal_name = Setting::where('slug',$slug)->value('principal_name');
        // dd($principal_name);
        return view('main.admin', compact('school_id','admins','settings','principal_name'));

    }

    public function reset(Request $request)
    {
      $id = User::find($request->id);
      $password = 'admin123';
      $id->password = Hash::make($password);
      $id->reset_time = $this->helper->date_np_con()." ".date("H:i:s");
      $id->save();
      return back();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
         $user= User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'user_type' => '1',
            'school_id' => $request['school_id'],
            'created_by' => Auth::user()->id,
            'is_active' => '1',
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
       
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return back()->with($pass)->withInput();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        try{
            return DB::transaction(function() use ($id){
            $users = User::where('id', $id)->get();
            $delete_user = User::find($id);

            $user_has_batch_id = UserHasBatch::where('user_id',$delete_user->id)->value('id');
            $delete_user_has_batch = UserHasBatch::find($user_has_batch_id);

            if($delete_user_has_batch){
                $delete_user_has_batch->delete();
            }
            if($delete_user->delete()){
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
        // return Response::json($notification);
          return back()->with($notification)->withInput();
        });
        }catch(\Exception $e){
            DB::rollback();
            dd($e);
            // throw $e;
        }
        DB::commit();
        
    }
}
