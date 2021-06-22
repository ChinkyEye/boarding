<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Helper\Helper;
use App\Book;
use App\SClass;
use App\Subject;
use Auth;
use Response;
use Validator;
use App\IssueBook;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{
    public function index()
    {
        return view('backend.librarysection.book.index');
    }
    public function getAllBook(Request $request)
    {
        $columns = array(
            0 =>'id', 
            1 =>'name',
            2 =>'class',
            3 =>'subject',
            4 =>'auther',
            5 =>'publisher',
            6 =>'quantity',
            7 =>'action',
        );
        $totalData = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $posts = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->with('getClass','getSubject')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->withCount(['getBookIssueHisab'])
                        ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $posts = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                        ->where('name', 'LIKE',"%{$search}%")
                        ->orWhere('auther', 'LIKE',"%{$search}%")
                        ->orWhere('book_no', 'LIKE',"%{$search}%")
                        ->with('getClass','getSubject')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->withCount(['getBookIssueHisab'])
                        ->get();
            $totalFiltered = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                                ->orWhere('name', 'LIKE',"%{$search}%")
                                ->orWhere('auther', 'LIKE',"%{$search}%")
                                ->orWhere('book_no', 'LIKE',"%{$search}%")
                                ->count();
        }
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
                $nestedData['name'] = $post->name." ".'<span class="badge badge-info">'.$post->book_no.'</span>';
                $nestedData['class'] = $post->getClass->name;
                $nestedData['subject'] = $post->getSubject->name;
                $nestedData['auther'] = $post->auther;
                $nestedData['publisher'] = $post->publisher;
                $nestedData['quantity'] = '<span class="badge badge-success">'.$post->quantity.'</span>'." / ".'<span class="badge badge-info">'.$post->get_book_issue_hisab_count.'</span>';
                $nestedData['action'] = "
                <div class='text-center'>
                <a href='".route('admin.book.show',$post->id)."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
                <a href='".route('admin.book.edit',$post->id)."' class='btn btn-xs btn-outline-info' data-toggle='tooltip' data-placement='top'title='Update'><i class='fas fa-edit'></i></a> 

                <form action='javascript:void(0)' data_url='".route('admin.book.destroy',$post->id)."' method='post' class='d-inline-block delete-confirm' data-toggle='tooltip' data-placement='top' title='Permanent Delete' onclick='myFunction(this)'>
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('is_active','1')->get();
        return view('backend.librarysection.book.create', compact('classes'));
    }

    public function getSubjectList(Request $request){
        $subjects = Subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->where('class_id', $request->class_id)
                            ->where('is_active','1')
                            ->get();
        return Response::json($subjects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['book_code'] = $this->helper->slug_converter($request['book_no']).'-'.Auth::user()->school_id;
        $this->validate($request, [
            'name' => 'required',
            'book_no' => 'required',
            'book_code' => 'required|unique:books',
            'class_id' => 'required',
            'subject_id' => 'required',
            'publisher' => 'required',
            'auther' => 'required',
            'quantity' => 'required|numeric',
        ]);
        
        $books = Book::create([
            'name' => $request['name'],
            'slug' => $this->helper->slug_converter($request['name']).'-'.rand(1000,9999),
            'book_no' => $request['book_no'],
            'book_code' => $request['book_code'],
            'class_id' => $request['class_id'],
            'subject_id' => $request['subject_id'],
            'publisher' => $request['publisher'],
            'auther' => $request['auther'],
            'quantity' => $request['quantity'],
            'school_id' => Auth::user()->school_id,
            'batch_id' => Auth::user()->batch_id,
            'created_by' => Auth::user()->id,
            'created_at_np' => $this->helper->date_np_con()." ".date("H:i:s"),
        ]);
        $pass = array(
            'message' => 'Data added successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.book.index')->with($pass);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $books = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        return view('backend.librarysection.book.show', compact('books'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $classes = SClass::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->get();
        // $class_id =Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id',$id)->value('class_id');
        // $subjects = Subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('class_id', $class_id)->get();
        // $books = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->where('id', $id)->get();
        // return view('backend.librarysection.book.edit', compact('books','classes','subjects'));

        $books = Book::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)->find($id);
        $subjects = Subject::where('school_id', Auth::user()->school_id)->where('batch_id', Auth::user()->batch_id)
                            ->where('class_id', $books->class_id)
                            ->where('is_active', True)
                            ->get();
        return view('backend.librarysection.book.edit', compact('books','subjects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {

        $request['book_code'] = $this->helper->slug_converter($request['book_no']).'-'.Auth::user()->school_id;
        $check_dub = Book::where('school_id',Auth::user()->school_id)->where('book_code',$request['book_code'] )->count();
        // dd($check_dub);
        if($check_dub == 0 || $request['book_code']  == $book->book_code){
          $this->validate($request, [
              'book_code' => 'required|min:2',
              'name' => 'required',
              'subject_id' => 'required',
              'publisher' => 'required',
              'auther' => 'required',
              'quantity' => 'required|numeric',
          ]);
          $main_data = $request->all();
          $main_data['updated_by'] = Auth::user()->id;
          if($book->update($main_data)){
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
              'book_code' => 'required|unique:books|min:2',
          ]);
        }
        return redirect()->route('admin.book.index')->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
      try{
        return DB::transaction(function() use ($book)
        {
          $issuebook_id =IssueBook::where('book_id', $book->id)->value('id');
          $delete_issuebook = IssueBook::find($issuebook_id);

          if($delete_issuebook){
            $delete_issuebook->delete();
          }
          if($book)
          {
            $book->delete();
            $notification = array(
              'message' => $book->name.' is deleted successfully!',
              'status' => 'success'
            );
          }
          else{
            $notification = array(
              'message' => $book->name.' could not be deleted!',
              'status' => 'error'
            );

          }
          return redirect()->route('admin.book.index')->with($notification);
        });
      } catch(\Exception $e){
        DB::rollback();
        throw $e;
        // dd($e);
        // abort(404);
      }
      DB::commit();
    }
}
