<?php

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Auth;

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
#region UserController
Route::get('/admin-page', function(){
    return "you are allowed!";
})->middleware("can:visitAdminPages"); // Gate middleware in AuthServiceProvider

#region account
Route::get('/', [UserController::class, "showHomepage"])->name("login");
Route::post('/register', [UserController::class, "register"])->middleware("guest");
Route::post('/login', [UserController::class, "login"])->middleware("guest");
Route::post('/logout', [UserController::class, "logout"])->middleware("mustBeLoggedIn");
#endregion
#region profile
Route::get('/profile/{user:username}', [UserController::class, "viewProfile"])->name('viewProfile');
Route::get('/profile/{user:username}/followers', [UserController::class, "viewProfileFollowers"]);
Route::get('/profile/{user:username}/followings', [UserController::class, "viewProfileFollowings"]);
Route::get('/view-manage-avatar', [UserController::class, "showAvatarForm"])->middleware("mustBeLoggedIn");
Route::post('/manage-avatar', [UserController::class, "UploadAvatar"]);

#endregion
#region following
Route::post('/create-follow/{user:username}',[FollowController::class,"createFollow"])->middleware("mustBeLoggedIn");
Route::post('/delete-follow/{user:username}',[FollowController::class,"deleteFollow"])->middleware("mustBeLoggedIn");
#endregion
#region PostContoller
Route::get('create-post', [PostController::class, "showCreatePost"])->name('createPost')->middleware("mustBeLoggedIn");
Route::post('/create-post', [PostController::class, "createPost"])->middleware("mustBeLoggedIn");
Route::get('/view-post/{post}', [PostController::class, "viewPost"]);
Route::get('/view-edit-post/{post}', [PostController::class, "viewEditPost"])->middleware('can:update,post');
Route::delete('/delete-post/{post}', [PostController::class, "deletePost"])->middleware('can:delete,post');
Route::put('/edit-post/{post}', [PostController::class, "updatePost"])->middleware('can:update,post');
Route::get('/search/{term}',[PostController::class, "search"]);
#endregion
#region chat
Route::post('/send-chat-message', function(Request $request){
    $values=$request->validate(['textvalue'=>'required']);
    //if(!trim(strip_tags($values['textvalue'])))
    //return response()->noContent();
    broadcast(new ChatMessage(['username'=>auth()->user()->username,
    'textvalue'=>$values['textvalue'],'avatar'=>auth()->user()->avatar]))->toOthers();
    return response()->noContent();

})->middleware("mustBeLoggedIn");
#endregion

#region rawJson
Route::middleware('cache.headers:public;max_age=20;etag')->group(function(){
    Route::get('/profile/{user:username}/raw', [UserController::class, "viewProfileRaw"])->name('viewProfileRaw');
    Route::get('/profile/{user:username}/followers/raw', [UserController::class, "viewProfileFollowersRaw"]);
    Route::get('/profile/{user:username}/followings/raw', [UserController::class, "viewProfileFollowingsRaw"]);
});

#endregion
