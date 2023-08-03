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
        

        $query = $this->getFilteredDocQuery($request);

        $items = $query->get();

        $response = array("data"=>$items);        

        return response()->json($response);

    }

    private function getFilteredDocQuery(Request $request){

        $author_id = $request->author_id;
        $subject_id = $request->subject_id;
        $category_id = $request->category_id;

        $file_date_start = $request->file_date_start;
        $file_date_end = $request->file_date_end;

        $search_term =trim($request->search_term);
        $search_in_title = $request->search_in_title == "true";
        $search_in_content = $request->search_in_content == "true";

        $query = Document::with("subjects");

        if ($author_id && $author_id != -1) {
            $query->where("author_id", "=", $author_id);
        }

        if ($subject_id && $subject_id != -1) {
            $query->whereRelation("subjects","subject_id", "=", $subject_id);
        }

        if ($category_id && $category_id != -1) {
            $ids = Category::where("main_category_id", "=", $category_id)->get()->pluck('id')->all();
            $ids[] = $category_id;
            $query->whereIn("category_id", $ids);
        }

        if ($file_date_start &&  $file_date_end) {

            $query->whereDate("date", ">=", $file_date_start);
            if ($file_date_end > $file_date_start)
                $query->whereDate("date", "<=", $file_date_end);            
        }

        if ($search_term && $search_in_title) {
            $query->where("name", "like", "%" . $search_term . "%");
        }

        return $query;
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
