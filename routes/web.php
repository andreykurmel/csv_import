<?php

use Illuminate\Support\Facades\Route;

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
    //\Illuminate\Support\Facades\Cache::put('some', 'about');
    dd(\Illuminate\Support\Facades\Cache::get('some'));
    return view('welcome');
});

//for VueRouter
Route::get('/{all}', function () {
    return view('welcome');
})->where('all', '.*');
