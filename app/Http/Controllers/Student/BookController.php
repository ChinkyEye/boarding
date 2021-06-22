<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Student;
use App\Subject;
use App\Book;
use App\IssueBook;
use Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $user_id = Auth::user()->id;
        // dd($user_id);
        $student_class = Student::where('user_id', $user_id)->value('class_id');
        $student_shift = Student::where('user_id', $user_id)->value('shift_id');
        $student_section = Student::where('user_id', $user_id)->value('section_id');
        // $student_info = Student::find($user_id);
        // dd($student_class,$student_shift,$student_section);
        $books = Book::where('school_id',Auth::user()->school_id)
                      ->where('batch_id',Auth::user()->batch_id)
                      ->where('section_id', $student_class) 
                      ->orderBy('id','DESC');

        $issuebooks = IssueBook::where('school_id',Auth::user()->school_id)
                      ->where('user_id',$user_id)
                      ->where('batch_id',Auth::user()->batch_id)
                      ->where('class_id', $student_class) 
                      ->where('shift_id', $student_shift) 
                      ->where('section_id', $student_section) 
                      ->orderBy('id','DESC'); 
                      // dd($issuebooks->get());             

        $subjects = Subject::where('class_id', $student_class)
                            ->where('school_id', Auth::user()->school_id)
                            ->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id','DESC');               
                      // dd($books->get());

        if($search != ''){
            $books = $books
            ->where('name', 'LIKE',"%{$search}%")
            ->orWhere('book_code',$search)
            ->orWhere('publisher', 'LIKE',"%{$search}%")
            ->orWhere('auther', 'LIKE',"%{$search}%")
            ;
            // dd($books->get());
        }
        // if($request->subject != ''){
        //     $subject = $request->subject;
        //     $books = $books->where('subject_id',$subject);
        //     // dd($books->get());
        // }

        $total = $issuebooks->count();
        // $issuebooks = $issuebooks->with('getUser','getClass','getSubject')->get();
        $issuebooks = $issuebooks->with('getBook','getClass')->get();
        $subjects = $subjects->with('getUser')->get();

        $response = [
            'booklist' => $issuebooks,
            'totallist' => $total,
            'subjectlist' => $subjects,
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
