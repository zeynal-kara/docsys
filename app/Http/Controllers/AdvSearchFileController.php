<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;

class AdvSearchFileController extends SearchFileController
{
    function index()
    {
        return view("adv-search-file");
    }

    function getAdvFilteredDoc() {
        
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
            'index' => 'docsys',
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
}
