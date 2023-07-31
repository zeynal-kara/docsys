<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Document;

class SearchFileController extends Controller
{
    public function index() {
        return view("search-file");
    }

    function getFilteredDoc(Request $request) {
        $author_id = $request->author_id;
        $file_date_start = $request->file_date_start;
        $file_date_end = $request->file_date_end;
        $subject_id = $request->subject_id;
        $category_id = $request->category_id;

        Document::where([
            []
        ]);

    }
    
    public function getUsers(Request $request){
        return $this->getData($request, User::class);
    }

    public function getSubjects(Request $request){
        return $this->getData($request, Subject::class);
    }

    public function getCategories(Request $request){
        return $this->getData($request, Category::class);
    }


    function getData(Request $request, $model) {
        $search = $request->search;

        if($search == ''){

            $items = $model::orderby('name','asc')->select('id','name')->limit(5)->get();

        }else{

            $items = $model::orderby('name','asc')
            ->select('id','name')->where('name', 'like', '%' .$search . '%')
            ->limit(5)->get();
        }

        $response = array();
        foreach($items as $item){
                $response[] = array( "id"=>$item->id, "text"=>$item->name);
        }

        return response()->json($response);
    }
}
