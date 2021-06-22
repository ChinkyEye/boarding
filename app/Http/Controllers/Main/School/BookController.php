<?php

namespace App\Http\Controllers\Main\School;

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

class BookController extends Controller
{
    public function index(Request $request)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_id = $this->schoolCheck($request)['school_id'];
        $school_info = $this->schoolCheck($request)['school_info'];

        $books = Book::where('school_id', $school_id)->get();
        $page = "Book List";
        return view('main.school.info.librarysection.book.index', compact('settings','school_info','books','page'));
    }

    public function getAllBook(Request $request)
    {
        $school_id = $this->schoolCheck($request)['school_id'];
        $school_info = $this->schoolCheck($request)['school_info'];
        $columns = array(
            0 =>'id', 
            1 =>'name',
            2 =>'auther',
            3 =>'quantity',
            4 =>'action',
        );
        $totalData = Book::where('school_id', $school_id)->where('is_active','1')->count();
        $totalFiltered = $totalData; 
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {            
            $posts = Book::where('school_id', $school_id)->where('is_active','1')->offset($start)
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $posts = Book::where('name', 'LIKE',"%{$search}%")
            ->orWhere('auther', 'LIKE',"%{$search}%")
            ->orWhere('quantity', 'LIKE',"%{$search}%")
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalFiltered = Book::where('is_active','1')
            ->orWhere('name', 'LIKE',"%{$search}%")
            ->orWhere('auther', 'LIKE',"%{$search}%")
            ->orWhere('quantity', 'LIKE',"%{$search}%")
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
                $nestedData['name'] = $post->name;
                $nestedData['auther'] = $post->auther;
                $nestedData['quantity'] = $post->quantity;
                $nestedData['action'] = "
                <div class='text-center'>
                    <a href='".route('main.book.show',[$school_info->slug,$post->slug])."' class='btn btn-xs btn-outline-success' data-toggle='tooltip' data-placement='top' title='View Detail'><i class='fa fa-eye'></i></a>
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

    public function show(Request $request, $school_slug, $slug)
    {
        $settings = $this->schoolCheck($request)['setting'];
        $school_info = $this->schoolCheck($request)['school_info'];
        // dd($request);
        $book_id = Book::where('school_id', $school_info->id)->where('slug', $slug)->value('id');
        $books = Book::where('school_id', $school_info->id)->where('slug', $slug)->find($book_id);
        $page = $books->name;
        return view('main.school.info.librarysection.book.show', compact('settings','school_info','books','page'));
    }

}
