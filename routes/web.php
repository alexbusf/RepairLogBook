<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('main');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/searchDate', [PostController::class, 'searchDate'])->name('posts.searchDate');
Route::resource('posts', PostController::class);
Route::post('/generate-pdf', [PostController::class, 'generatePDF'])->name('generate.pdf');


Route::group(['middleware' => ['auth']], function() {
    //Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
    //Route::get('/posts/searchDate', [PostController::class, 'searchDate'])->name('posts.searchDate');
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    //Route::resource('posts', PostController::class);
    //Route::post('/generate-pdf', [PostController::class, 'generatePDF'])->name('generate.pdf');
    Route::resource('categories', CategoryController::class);
    Route::get('/users/{userId}/posts', [PostController::class, 'byuser'])->name('users.posts.index');
    Route::get('/categories/{categoryId}/posts', [PostController::class, 'bycategory'])->name('categories.posts.index');
});



