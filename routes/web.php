<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
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
    return view('welcome');
});

Route::get('story-category', [PostController::class, 'storyCategory']);
Route::get('story_information', [PostController::class, 'storyInformation']);
Route::get('doc_story', [PostController::class, 'docStory']);
Route::get('get_data/{id}', [PostController::class, 'getData']);
Route::get('get_advertisement', [PostController::class, 'getAdvertisement']);
Route::get('get_dom_parser', [PostController::class, 'getDomHtml']);
Route::get('get_link', [PostController::class, 'getLink']);
Route::get('get_story', [PostController::class, 'getStory']);
Route::get('insert_story', [PostController::class, 'insertStory']);
Route::get('update_story', [PostController::class, 'updateStory']);
Route::get('insert_story_multi', [PostController::class, 'insertStoryMulti']);
Route::get('insert_chapter_multi', [PostController::class, 'insertChapterMulti']);
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('logout', [LoginController::class, 'Logout'])->name('logout');
Route::post('login', [LoginController::class, 'Login'])->name('check.login');
Route::get('signup', [LoginController::class, 'Signup']);
Route::post('signup', [LoginController::class, 'Register'])->name('signup');
//Story
Route::group(['middleware' => 'checklogin','prefix' => 'story', 'as' => 'story.'], function () {
    Route::get('/', [StoryController::class, 'index'])->name('index');
    Route::post('link', [StoryController::class, 'addLink'])->name('link');
    Route::get('create', [StoryController::class, 'create'])->name('create');
    Route::post('store', [StoryController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [StoryController::class, 'destroy'])->name('destroy');
    Route::get('show/{id}', [StoryController::class, 'show'])->name('show');
    Route::post('update/{id}', [StoryController::class, 'update'])->name('update');
});

//Chapter
Route::group(['middleware' => 'checklogin','prefix' => 'chapter', 'as' => 'chapter.'], function () {
    Route::get('/', [ChapterController::class, 'index'])->name('index');
    Route::post('link', [ChapterController::class, 'addLink'])->name('link');
    Route::get('create', [ChapterController::class, 'create'])->name('create');
    Route::post('store', [ChapterController::class, 'store'])->name('store');
    Route::delete('destroy/{id}', [ChapterController::class, 'destroy'])->name('destroy');
    Route::get('show/{id}', [ChapterController::class, 'show'])->name('show');
    Route::post('update/{id}', [ChapterController::class, 'update'])->name('update');
});





