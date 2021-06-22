<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Helper\Helper;
use App\IssueBook;
use App\Shift;
use App\SClass;
use App\Section;
use App\Student;
use App\Book;
use Response;
use Auth;
use App\User;

class IssueBookController extends Controller
{

    public function index()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active','1')
                        ->get();
        // $date = Date('Y-m-d'); ->where('date', $nepali_date)
        $nepali_date = $this->helper->date_np_con_parm(Date('Y-m-d'));
                        
        $issuebooks = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                ->where('issue_date',$nepali_date)
                                ->orderBy('id','DESC')
                                ->get();
        return view('library.issuebook.index', compact('shifts','issuebooks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shifts = Shift::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('is_active','1')
                        ->get();
        return view('library.issuebook.create', compact('shifts'));
    }

    public function getBookList(Request $request){
      $book_list = Book::where('school_id',Auth::user()->school_id)
                        ->where('class_id',$request->class_id)
                        ->where('is_active','1')
                        ->with('getSubject')
                        ->get();
      return Response::json($book_list);
    }

    public function getStudentList(Request $request)
    { 
      $student_list = Student::where('school_id',Auth::user()->school_id)
                            ->where('shift_id', $request->shift_id)
                            ->where('class_id', $request->class_id)
                            ->where('section_id', $request->section_id)
                            ->where('is_active','1')
                            ->with('getStudentUser')
                            ->get();
      return Response::json($student_list);
    }   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $user_id = Student::where('id', $request->student_id)->value('user_id');
        // $students = User::find($user_id)->id;
      
      $selectBookCount = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('book_id',$request['book_id'])->where('is_return','0')->count();
      $bookQuantity = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$request['book_id'])->value('quantity');
      // dd($bookQuantity,$selectBookCount);
     
        $user_id = Student::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $request->student_id)->value('user_id');
        $this->validate($request, [
            'shift_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'book_id' => 'required',
            'student_id' => 'required',
            'issue_date' => 'required',
        ]);
        $book_id = $request->book_id;

        if($selectBookCount == $bookQuantity){
          $pass = array(
            'message' => 'Book  is not in stock!',
            'alert-type' => 'error'
          );
         return back()->with($pass);

          // dd('hh');
          
        }
        else{
          $issuebook = IssueBook::create([
              'shift_id' => $request['shift_id'],
              'class_id' => $request['class_id'],
              'section_id' => $request['section_id'],
              'book_id' => $request['book_id'],
              'student_id' => $request['student_id'],
              'user_id' => $user_id,
              'issue_date' => $request['issue_date'],
              'issue_date_en' => $this->helper->date_eng_con_parm($request['issue_date']),
              'school_id' => Auth::user()->school_id,
              'batch_id' => Auth::user()->batch_id,
              'created_by' => Auth::user()->id,
              'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
          ]);

          $pass = array(
              'message' => 'Book issued successfully!',
              'alert-type' => 'success'
          );

        }
        return redirect()->route('library.issuebook.index')->with($pass);
    }

    public function getReturnDate(Request $request,$data_id)
    {
        $data_list = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->find($data_id);
        return response()->json($data_list);
    }

    public function getReturnDateSave(Request $request)
    {
      $data_save = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->find($request->data_id);
      $book_id = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->value('book_id');
    

      $request['return_date'] = $request->return_date;
      $request['return_date_en'] = $this->helper->date_eng_con_parm($request->return_date);
      $request['updated_by'] = Auth::user()->id;
      $data_save->is_return = '1';
      if($data_save->update($request->all())){
        $notification = array(
          'message' => 'Book has return',
          'status' => 'success'
        );
      }
      else{
        $notification = array(
          'message' => 'Book could not be return!',
          'status' => 'error'
        );
      };
      return back()->withInput()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      // dd('bitch');
        $issuebooks = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('library.issuebook.show', compact('issuebooks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $issuebooks = IssueBook::find($id);
      $books = Book::where('school_id',Auth::user()->school_id)->where('batch_id',Auth::user()->batch_id)->where('class_id', $issuebooks->class_id)->get(); 
      $students = Student::where('school_id',Auth::user()->school_id)
                          ->where('batch_id',Auth::user()->batch_id)
                          ->where('shift_id', $issuebooks->shift_id)
                          ->where('class_id', $issuebooks->class_id)
                          ->where('section_id', $issuebooks->section_id)
                          ->get();
      // dd($issuebooks,$students);
      return view('library.issuebook.edit', compact('issuebooks','students','books'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueBook $issuebook)
    {
        $this->validate($request, [
            'book_id' => 'required',
            'student_id' => 'required',
        ]);
        // dd($request);
        $request['updated_by'] = Auth::user()->id;
        $find =  $request->student_id;
        $finduser = Student::find($find);
        // dd($finduser,$find);
        $request['user_id'] = $finduser->user_id;

        if($issuebook->update($request->all())){
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
        return redirect()->route('library.issuebook.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueBook $issuebook)
    {
      dd("botch");
      // hiiden
      $issuebook->where('school_id',Auth::user()->school_id)->where('batch_id',Auth::user()->batch_id);
      if($issuebook->delete()){
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
      return back()->with($notification);
      // return Response::json($notification);
    }

    public function getIssuebookRecord(Request $request){
        $issuebooks = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->orderBy('id', 'DESC');
        if(!empty($request->shift_data))
        {            
          $issuebooks = $issuebooks->where('shift_id', $request->shift_data);
        }

        if(!empty($request->class_data))
        {            
          $issuebooks = $issuebooks->where('class_id', $request->class_data);
        }

        if(!empty($request->section_data))
        {            
          $issuebooks = $issuebooks->where('section_id', $request->section_data);
        }

        if(!empty($request->student_data))
        {            
          $issuebooks = $issuebooks->where('student_id', $request->student_data);
        }

        if(!empty($request->date_data))
        {            
          $issuebooks = $issuebooks->where('issue_date', $request->date_data);
        }

        $issuebooks = $issuebooks->get();
        $date = $request->date_data;
        $issuebooks_count = count($issuebooks);
        return view('library.issuebook.index-ajax', compact('issuebooks_count','issuebooks','date'));
    }

    public function getStudentIssueList(Request $request){
      $issuebooks = IssueBook::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->where('student_id',$request->student_id)
                            ->whereNull('return_date')
                            ->orderBy('id', 'DESC')
                            ->get();
      $issuebooks_count = count($issuebooks);
        return view('library.issuebook.create-ajax', compact('issuebooks_count','issuebooks'));
    }
   
}
