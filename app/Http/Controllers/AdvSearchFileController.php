<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;

class AdvSearchFileController extends Controller
{
    function index()
    {
        return view("adv-search-file");
    }

    function getAdvFilteredDoc() {

        $raw_data = $this->getDataFromElastic();
        $hits = $raw_data["hits"]["hits"];
        $recordsFiltered = $raw_data["hits"]["total"]["value"];

        $response = ["data"=>$hits, "recordsFiltered"=> $recordsFiltered];
        return response()->json($response);
    }

    function getDataFromElastic() {
        $hosts = [
            //'http://user:pass@localhost:9200',       // HTTP Basic Authentication
            'http://localhost:9200',
        ];
        
        $client = ClientBuilder::create()
                            ->setHosts($hosts)
                            ->build();

        //$response = $client->info();
        
       return $client->search($this->getElasticParams());
    }

    function getElasticParams()  {

        $from  = request("start") ?? 0; 
        $search_term  = request("content_search_term") ?? "";
        $search_type = request("search_type");
       
        $query = [
                'bool' => [
                    'must' => [
                        ['match'=> [ 'content'=> $search_term ] ]
                    ],
                    'filter' => [
                        ['term'=> [ "meta.raw.dsfile_type" => $search_type ] ]
                    ]
                ]
            ];
        
        $highlight = [
            "pre_tags" => ["<mark><strong>"],
            "post_tags" => ["</strong></mark>"],
            "fields" => [ "content"=> (object) [] ]
        ];

        $params = [
            'index' => 'docsys',
            'body'  => [
                'query' => $query,
                'highlight'=> $highlight,
                "size" => 10,
                "from"=>$from
            ]
        ];

        return $params;
    }

}
