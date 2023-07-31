<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Subject;

class SearchFileController extends Controller
{
    function index() {
        return view("search-file");
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
