<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('import')->group(function () {
    Route::post('csv_prepare', 'Api\CsvImportController@prepareCsv');
    Route::post('csv_store', 'Api\CsvImportController@storeCsv');
});

Route::prefix('products')->group(function () {
    Route::post('get', 'Api\ProductsController@get');
});
