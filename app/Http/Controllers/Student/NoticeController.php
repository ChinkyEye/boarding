<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use App\Notice_for;
use Auth;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $total = '0';
        $student_class = Student::where('user_id', $user_id)->pluck('class_id');
        $notices = Notice_for::where('school_id',Auth::user()->school_id)
                    ->where('batch_id',Auth::user()->batch_id)
                    ->where('class_id',$student_class)
                    ->orderBy('id','DESC');
        if($request->search != ''){
            $notices = $notices->WhereHas('getNotice', function( $query ) use ( $request ){
                                    $query->where('title', 'LIKE',"%{$request->search}%")
                                    ->where('is_active', true);
                                });
        }
        if($request->startdate == Date('Y-m-d') && $request->enddate == Date('Y-m-d')){
            $notices = $notices->WhereHas('getNotice', function( $query ) use ( $request ){
                                        $query->where('start_date','<=',$request->startdate)->where('end_date','>=',$request->enddate)
                                        ->where('is_active', true);
                                    });
        }elseif ($request->startdate && $request->enddate) {
            $notices = $notices->WhereHas('getNotice', function( $query ) use ( $request ){
                                        $query->whereBetween('start_date',[$request->startdate,$request->enddate])
                                        ->where('is_active', true);
                                    });
        }
        $total = $notices->count();
        $notices = $notices->with('getUser','getNotice')->get();          

        $response = [
            'noticelist' => $notices,
            'totallist' => $total,
        ];
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        // dd($request, $slug);
        $user_id = Auth::user()->id;

        $student_class = Student::where('user_id', $user_id)->pluck('class_id');
        $notices = Notice_for::where('school_id',Auth::user()->school_id)
                    ->where('class_id',$student_class)
                    ->WhereHas('getNotice', function( $query ) use ( $request ,$slug ){
                        $query->where('slug',$slug)
                        ->where('is_active', true);
                    });
        $notices = $notices->with('getUser','getNotice')->get();          

        $response = [
            'notices' => $notices,
        ];
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
