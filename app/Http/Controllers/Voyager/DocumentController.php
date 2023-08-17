<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use MonstreX\VoyagerExtension\Controllers\VoyagerExtensionBaseController;

class DocumentController extends VoyagerExtensionBaseController
{
    function index(Request $request)
    {
        return parent::index($request);
    }
}
