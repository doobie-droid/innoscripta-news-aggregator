<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view('/documentation', 'docs');

Route::get('/docs/openapi.yaml', function () {
    $yaml = file_get_contents(storage_path('app/scribe/openapi.yaml'));

    return Response::make($yaml, 200, [
        'Content-Type' => 'text/plain',
    ]);
});
