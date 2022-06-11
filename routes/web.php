<?php

use App\Http\Controllers\PostController;
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
Route::get('get_dom_parser', [PostController::class, 'getDom']);
Route::get('get_link', [PostController::class, 'getLink']);
Route::get('get_story', [PostController::class, 'getStory']);
Route::get('insert_story', [PostController::class, 'insertStory']);
Route::get('update_story', [PostController::class, 'updateStory']);


