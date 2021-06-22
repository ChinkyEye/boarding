<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use Validator;
use Response;
use File;
use App\Class_has_shift;
use App\Class_has_section;

class StudentController extends Controller
{
  public function getClassList(Request $request){
    $shift_id = $request->shift_id;
    $class_list = Class_has_shift::where('shift_id',$shift_id)
    ->where('is_active','1')
    ->where('school_id',Auth::user()->school_id)
    ->with('getClass')
    ->get();
    return Response::json($class_list);
  }

  public function getSectionList(Request $request){
    $class_id = $request->class_id;
    $shift_id = $request->shift_id;
    $section_list = Class_has_section::where('shift_id',$shift_id)
    ->where('class_id',$class_id)
    ->where('is_active','1')
    ->where('school_id',Auth::user()->school_id)
    ->with('getSection')
    ->get();
    return Response::json($section_list);
  }
}
