<?php

use App\Http\Controllers\Api\UserAvatarsController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Auth\RegisterConfirmationsController;
use App\Http\Controllers\BestRepliesController;
use App\Http\Controllers\ChannelsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\LockedThreadsController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\ThreadSubscriptionsController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\Api\UserStarsController;
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

Route::get('/', [ThreadsController::class, 'index']);

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/threads', [ThreadsController::class, 'index'])->name('threads');
Route::get('/threads/create', [ThreadsController::class, 'create'])->middleware('must-be-confirmed');
Route::get('/threads/search', [SearchController::class, 'show']);
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);
Route::get('/threads/{channel}/{thread}', [ThreadsController::class, 'show']);
Route::delete('/threads/{channel}/{thread}', [ThreadsController::class, 'destroy']);
Route::post('/threads', [ThreadsController::class, 'store'])->middleware('must-be-confirmed');
Route::post('/threads/{channel}/{thread}', [ThreadsController::class, 'update']); // Because PATCH does not support FormData
Route::patch('/threads/{channel}/{thread}', [ThreadsController::class, 'update']); // For testing purposes

Route::post('/channels', [ChannelsController::class, "store"]);

Route::post('/locked-threads/{thread}', [LockedThreadsController::class, 'store'])->middleware('admin')->name('locked-threads.store');
Route::delete('/locked-threads/{thread}', [LockedThreadsController::class, 'destroy'])->middleware('admin')->name('locked-threads.destroy');

Route::get('/threads/{channel}/{thread}/replies', [RepliesController::class, 'index']);
Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class, 'store'])->middleware('must-be-confirmed');
Route::post('/replies/{reply}', [RepliesController::class, 'update']); // Because PATCH does not support FormData
Route::patch('/replies/{reply}', [RepliesController::class, 'update']); // For testing purposes
Route::delete('/replies/{reply}', [RepliesController::class, 'destroy']);

Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class, 'store']);
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class, 'destroy']);

Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy']);

Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');
Route::get('/profiles/{user}/notifications', [UserNotificationsController::class, 'index']);
Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy']);

Route::get('/api/users', [UsersController::class, 'index']);
Route::post('/api/users/{user}/avatar', [UserAvatarsController::class, 'store'])->name('avatar');
Route::post('/api/users/{user}/stars', [UserStarsController::class, 'store']);

Route::get('/register/confirm', [RegisterConfirmationsController::class, 'index'])->name('register.confirm');

// Route::post('/replies/{reply}/best', [BestRepliesController::class, 'store'])->name('best-replies.store');