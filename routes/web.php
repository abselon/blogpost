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
    return '<h1> Home page </h1> <a href="/about"> go to about page <a>';
});

Route::get('/about', function(){
    return '<h1> about page </h1> <a href="/"> Back to home <a>';
});