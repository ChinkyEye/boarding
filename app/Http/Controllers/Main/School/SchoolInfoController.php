<?php

namespace App\Http\Controllers\Main\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use Auth;
use Response;
use App\Setting;
use App\SClass;
use App\Section;
use App\Student;
use App\Teacher;
use App\Book;
use App\Shift;
use App\User;

class SchoolInfoController extends Controller
{
    public function index(Request $request)
    {
        // var_dump("expression"); die();
        $adminInfo = User::where('school_id',Auth::user()->id)->count();
        // var_dump($adminInfo); die();
    	$settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        // count
        $students = Student::where('school_id',$school_info->id)->where('is_active', True)->count();
        $teachers = Teacher::where('school_id',$school_info->id)->where('is_active', True)->count();
        $books = Book::where('school_id',$school_info->id)->where('is_active', True)->count();
        $shifts = Shift::where('school_id',$school_info->id)->where('is_active', True)->count();
        // dd($teachers);
        $count_list = collect([
                            ['student' => $students], 
                            ['teacher' => $teachers], 
                            ['book' => $books], 
                            ['shift' => $shifts], 
                        ])->collapse()->all();
    	return view('main.school.info.dashboard',compact(['settings','school_info','count_list']));
    }

    public function getClassList(Request $request){
    	$data_id = $request->input('shift_id');
        dd($data_id);
        $class_list_data = Class_has_shift::where('shift_id',$data_id)
        ->where('is_active','1')
        ->where('school_id',Auth::user()->school_id)
        ->with('getClass')
        ->get();
        dd($class_list_data);
    	// $class_list_data = SClass::whereHas('getClassList', function (Builder $query) use ($data_id) {
    	// 	$query->where('shift_id', $data_id);
    	// })
    	// ->get();
    	return Response::json($class_list_data);
    }
    public function getSectionList(Request $request){
    	$data_id = $request->input('data_id');
    	$shift_id = $request->input('shift_id');
    	$section_list_data = Section::whereHas('getSectionList', function (Builder $query) use ($data_id,$shift_id) {
    		$query->where('class_id', $data_id)->where('shift_id', $shift_id);
    	})
    	->get();
    	return Response::json($section_list_data);
    }
}
