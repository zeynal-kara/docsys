<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class FilesController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $path)
    {
        if (str_contains(url()->previous(), '/admin/login') &&
            !str_contains($path, "doc_root"))
        {
            return $this->viewFile($path);
        }
        
        if (Auth::check()) {
            return $this->viewFile($path);
        }

        abort(403, "Login Required.");
       
    }

    function viewFile($path) {

        abort_if(
            ! Storage::disk('files') ->exists($path),
            404,
            "The file doesn't exist. Check the path."
        );

        return Storage::disk('files')->response($path);        
    }
}
