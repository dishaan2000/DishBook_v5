<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SocialMediaController;

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
Route::get('/', function () { return view('welcome'); });

Route::get('/social', [SocialMediaController::class, 'index'])->name('social.index');
Route::get('/index', [SocialMediaController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    
    Route::get('/social', [SocialMediaController::class, 'index'])->name('social.index');
    Route::post('/social/create-post', [SocialMediaController::class, 'createPost'])->name('social.createPost');
    Route::post('/social/like-post/{postId}', [SocialMediaController::class, 'likePost'])->name('social.likePost');
    Route::delete('/unlike-post/{postId}', [SocialMediaController::class, 'unlikePost'])->name('social.unlikePost');
    Route::post('/social/add-comment/{postId}', [SocialMediaController::class, 'addComment'])->name('social.addComment');
    Route::get('/social/following/{userId}', [SocialMediaController::class, 'userFollowing'])->name('social.following');
    Route::post('/social/follow/{userId}', [SocialMediaController::class, 'followUser'])->name('social.followUser');
    Route::delete('/social/unfollow/{userId}', [SocialMediaController::class, 'unfollowUser'])->name('social.unfollowUser');

});

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
