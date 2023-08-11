<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchFileController;
use App\Http\Controllers\FilesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name("login");

Route::get('/files/{path}', FilesController::class)->where('path', '^(.+)\/([^\/]+)$');


Route::group(['prefix' => 'admin'], function () {
    
    Voyager::routes();

    Route::get("/search-file", [SearchFileController::class, "index"])->middleware("admin.user");

    Route::post('/getUsers', [SearchFileController::class, 'getUsers'])->name('getUsers');
    Route::post('/getSubjects', [SearchFileController::class, 'getSubjects'])->name('getSubjects');
    Route::post('/getCategories', [SearchFileController::class, 'getCategories'])->name('getCategories');
    Route::post('/getFilteredDoc', [SearchFileController::class, 'getFilteredDoc'])->name('getFilteredDoc');
});
