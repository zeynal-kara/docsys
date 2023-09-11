<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use MonstreX\VoyagerExtension\Controllers\VoyagerExtensionBaseController;
use App\Customizations\Media;
use Illuminate\Support\Facades\Storage;
use Elasticsearch\ClientBuilder;

class DocumentController extends VoyagerExtensionBaseController
{
    function index(Request $request)
    {
        return parent::index($request);
    }

    function destroy(Request $request, $id)
    {
        $ret = parent::destroy($request, $id);
        $this->deleteElasticRecord($this->getIds());
        return $ret;
    }

    function getIds() {

        $ids = request("ids");
        $ids = explode(",", $ids);
        $_ids = "";

        $i=0; $length = count($ids);
        foreach ($ids as $id) {
           $_ids .= "'" .$id . ($length > $i+1 ? "'," : "'");
           $i++;
        }
        
        return "[" . $_ids . "]";
    }

    function deleteElasticRecord($id){
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
                        'match' => [
                            "meta.raw.dsfile_id"=> $id
                        ]
                    ]
            ]
        ];
        
       return $client->deleteByQuery($params);
    }

    function store(Request $request)
    {
        $ret = parent::store($request);

        $media = Media::latest()->first();
        $path  = "";
        if ($media) {

            $path = "/doc_root/" . $media->file_key . "/org_" . $media->file_key . ".pdf";
            $meta_arr = [
                "dsfile_id" => $media->file_key
            ];

            $this->runPythonScript($path, $meta_arr);
        }
        
        return $ret;
    }

    public function runPythonScript(string $path, Array $meta_arr)
    {
        $py_path = Storage::disk('local')->path("py_script/add_meta.py");
        $file_path = Storage::disk('files')->path($path);

        $py_path = str_replace("\\", "/", $py_path);
        $file_path = str_replace("\\", "/", $file_path);

        $comm = $this->generatePyCommand($py_path, $file_path,  $meta_arr);
        $process = shell_exec($comm);
        
        //dd([$comm, $process]);
        return $file_path;
    }

    function getMetaJson(Array $metaArr) {
        //ex: {\'/YenMetam\': \'Test Metamm Zeyn\'}
        $json = '';
        $i = 0; $length = count($metaArr);
        foreach ($metaArr as $key => $value) {
            $i++;
            $json .=  ' \'/' . $key .  '\': ' . ' \'' . $value . '\' ' 
                    . ($length > $i ? "," : "");
        }
        
        return '{ ' . $json . '} ';
    }

    function generatePyCommand(string $pyScriptpath, string $filePath, Array $metaArr) {

        $command = 'py ":py_script_path"  -path ":file_path" -meta_arr ":meta_arr"';

        $command = str_replace(":py_script_path", $pyScriptpath, $command);
        $command = str_replace(":file_path", $filePath, $command);
        $command = str_replace(":meta_arr", $this->getMetaJson($metaArr), $command);

        return  $command;
    }
    
}
