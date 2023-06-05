<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

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

//user related routes for login/register
Route::get('/', [UserController::class, "showcorrecthomepage"])->name('login');

Route::post('/register', [UserController::class, 'register'])->middleware('guest');

Route::post('/login', [UserController::class, 'login'])->middleware('guest');

Route::post('/logout', [UserController::class, 'logout'])->middleware('mustbeloggedin');

//Blog post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustbeloggedin');

Route::post('/create-post', [PostController::class, 'storenewpost'])->middleware('mustbeloggedin');

Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->middleware('mustbeloggedin');

Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');

Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');

Route::put('/post/{post}', [PostController::class, 'actuallyUpdate'])->middleware('can:update,post');

//Profile Related Routes
Route::get('/profile/{user:username}', [UserController::class, 'profile']);