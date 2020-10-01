<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ThreadsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/threads', [ThreadsController::class, 'index']);
Route::get('/threads/create', [ThreadsController::class, 'create']);
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);
Route::get('/threads/{channel}/{thread}', [ThreadsController::class, 'show']);
Route::post('/threads', [ThreadsController::class, 'store']);

Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class, 'store']);

Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);

Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');
