<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Subject;
use App\Models\Document;
use Elasticsearch\ClientBuilder;

class SearchFileController extends Controller
{
    public function index() {
        return view("search-file");
    }

    function getFilteredDoc(Request $request) {


        $items = $this->getFilteredDocQuery($request);

        $response = array("data"=>$items);

        return response()->json($response);

    }

    private function getFilteredDocQuery(Request $request){

        $author_id = $request->author_id;
        $subject_id = $request->subject_id;
        $category_id = $request->category_id;

        $file_date_start = $request->file_date_start;
        $file_date_end = $request->file_date_end;


        $query = Document::with(["subjects", "author", "category", "_media"]);

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

        return $this->queryDbOrElactic($request, $query);
    }

    function queryDbOrElactic(Request $request, $query) {

        $content_search_term =trim($request->content_search_term);
        $name_search_term =trim($request->name_search_term);

        if (!$content_search_term) {

            return $name_search_term ? $query->where("name", "like", "%" . $name_search_term . "%")->get() :
                                       $query->get();
        }
        
        return $this->getDataFromElastic($query);
    }

    function getDataFromElastic($query) {

        $hosts = [
            //'http://user:pass@localhost:9200',       // HTTP Basic Authentication
            'http://localhost:9200',
        ];
        
        $client = ClientBuilder::create()
                            ->setHosts($hosts)
                            ->build();

        //$response = $client->info();

        $params = [
            'index' => 'data_science_index',
            'body'  => [
                'query' => [
                    'regexp' => [
                        'file.filename' => '.*g.*'
                    ]
                ]
            ]
        ];
        
        $response = $client->search($params);
        print_r($response); exit;

        //dd($response);
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
