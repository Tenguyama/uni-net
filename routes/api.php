<?php

use App\Http\Controllers\Api\v1\ChatController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\CommentLikeController;
use App\Http\Controllers\Api\v1\CommunityController;
use App\Http\Controllers\Api\v1\CommunityManagerController;
use App\Http\Controllers\Api\v1\ComplaintController;
use App\Http\Controllers\Api\v1\ConsumerController;
use App\Http\Controllers\Api\v1\FakultController;
use App\Http\Controllers\Api\v1\FollowController;
use App\Http\Controllers\Api\v1\GroupController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\MessageController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\Api\v1\PostLikeController;
use App\Http\Controllers\Api\v1\ThemeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')
    ->name('v1')
    ->group(callback: function () {
        Route::middleware('auth:sanctum')->group(function () {
            //Follow
            Route::group(['prefix' => 'follow'], function (){
                //follow +
                Route::post('/', [FollowController::class, 'follow']);
            });
            //Complaint
            Route::group(['prefix' => 'complaint'], function (){
                //create +
                Route::post('/create', [ComplaintController::class, 'create']);
            });
            //Consumer
            Route::group(['prefix' => 'consumer'], function() {
                //logout                +
                //update                +
                //delete                +
                //authorize-profile     +
                Route::delete('/logout', [ConsumerController::class, 'logout']);
                Route::put('/update', [ConsumerController::class, 'update']);
                Route::delete('/delete', [ConsumerController::class, 'delete']);
                Route::get('/authorize-profile', [ConsumerController::class,'authorizeProfile']);
            });
            //Community
            Route::group(['prefix' => 'community'], function(){
                Route::post('/create', [CommunityController::class, 'create']);
                Route::put('/update/{community}',  [CommunityController::class, 'update']);
                Route::delete('/delete/{community}',  [CommunityController::class, 'delete']);
                //CommunityManager
                Route::group(['prefix'=>'manager'], function (){
                    //create    +
                    //delete    +
                    Route::post('/create', [CommunityManagerController::class, 'create']);
                    Route::delete('/delete', [CommunityManagerController::class, 'delete']);
                });
            });
            //Comment
            Route::group(['prefix'=>'comment'], function () {
                //create    +
                //update    +
                //delete    +
                Route::post('/create', [CommentController::class, 'create']);
                Route::put('/update', [CommentController::class, 'update']);
                Route::delete('/delete/{comment}', [CommentController::class, 'delete']);
                //CommentLike
                Route::group(['prefix' => 'like'], function () {
                    //create    +
                    //update    +
                    //delete    +
                    Route::post('/create', [CommentLikeController::class, 'create']);
                    Route::put('/update', [CommentLikeController::class, 'update']);
                    Route::delete('/delete', [CommentLikeController::class, 'delete']);
                });
            });
            //Post
            Route::group(['prefix'=>'post'], function () {
                //getAllPostByTrackable     +
                //create                    +
                //update                    +
                //delete                    +
                Route::get('/trackable', [PostController::class, 'getAllPostByTrackable']);
                Route::post('/create', [PostController::class, 'create']);
                Route::put('/update/{post}', [PostController::class, 'update']);
                Route::delete('/delete/{post}', [PostController::class, 'delete']);
                //PostLike
                Route::group(['prefix' => 'like'], function () {
                    //create    +
                    //update    +
                    //delete    +
                    Route::post('/create', [PostLikeController::class, 'create']);
                    Route::put('/update', [PostLikeController::class, 'update']);
                    Route::delete('/delete', [PostLikeController::class, 'delete']);
                });
            });
            //Fakult
            Route::group(['prefix'=>'fakult'], function(){
                //select +
                Route::get('/select/{fakult}', [FakultController::class ,'select']);
            });
            //Group
            Route::group(['prefix'=>'group'], function(){
                //select +
                Route::get('/select/{group}', [GroupController::class ,'select']);
            });
            //Message
            Route::group(['prefix'=>'message'],function(){
                //create    +
                //update    +
                //delete    +
                Route::post('/create', [MessageController::class, 'create']);
                Route::put('/update', [MessageController::class, 'update']);
                Route::delete('/delete', [MessageController::class, 'delete']);
            });
            //Chat
            Route::group(['prefix'=>'chat'], function (){
                //-getChatById              +
                //-getLastMessages          +
                //-findOrCreateSoloChat     +
                //-createMultiChat          +
                //-addPermission            +
                //-addConsumerToChat        +
                //-updateChat               +
                //-deleteConsumerForChat    +
                //-deleteChat               +
                Route::get('/get/{chat}', [ChatController::class,'getChatById']);
                Route::post('/last-messages', [ChatController::class, 'getLastMessages']);
                Route::get('/find-or-create-solo-chat/{consumer}', [ChatController::class,'findOrCreateSoloChat']);
                Route::post('/create-multi-chat', [ChatController::class, 'createMultiChat']);
                Route::post('/add-permission', [ChatController::class, 'addPermission']);
                Route::post('/add-consumer-to-chat', [ChatController::class, 'addConsumerToChat']);
                Route::put('/update', [ChatController::class, 'updateChat']);
                Route::delete('/delete-consumer-from-chat/', [ChatController::class, 'deleteConsumerForChat']);
                Route::delete('/delete/{chat}', [ChatController::class, 'deleteChat']);
            });

        });
        Route::middleware('guest')->group( function () {
            //Consumer
            Route::group(['prefix' => 'consumer'], function() {
                //registerWithProvider  +
                //loginWithProvider     +
                //login                 +
                Route::post('/register-with-provider', [ConsumerController::class, 'registerWithProvider']);
                Route::post('/login-with-provider', [ConsumerController::class, 'loginWithProvider']);
                Route::post('/login', [ConsumerController::class, 'login']);
            });
        });
        //Post
        Route::group(['prefix'=>'post'], function () {
            //getPostById               +
            //getAllRecommendationPost  +
            Route::get('/by-id/{post}', [PostController::class, 'getPostById']);
            Route::get('/recommendations', [PostController::class, 'getAllRecommendationPost']);
        });
        //Consumer
        Route::group(['prefix'=>'consumer'], function (){
            //search        +
            //getProfile    +
            Route::post('/search', [ConsumerController::class, 'search']);
            Route::get('/get-profile/{consumer}', [ConsumerController::class,'getProfile']);
        });
        //Community
        Route::group(['prefix'=>'community'], function (){
            //search        +
            //getProfile    +
            Route::post('/search', [CommunityController::class, 'search']);
            Route::get('/get-profile/{community}', [CommunityController::class,'getProfile']);
        });
        //Follow
        Route::group(['prefix'=>'follow'],function (){
            //getFollowers +
            //getTrackable +
            Route::post('/get-followers', [FollowController::class,'getFollowers']);
            Route::get('/get-trackable/{consumer}', [FollowController::class,'getTrackable']);
        });
        //Language
        Route::group(['prefix'=>'language'], function (){
            //ONLY FOR ADMIN PANEL
            //create +
            //update +
            //delete +
            //API
            //getAll +
           Route::get('/all' , [LanguageController::class, 'getAll']);
        });
        //Theme
        Route::group(['prefix'=>'theme'], function (){
            //getAllByLanguage +
           Route::get('/all/{language}', [ThemeController::class,'getAllByLanguage']);
        });
        //Fakult
        Route::group(['prefix'=>'fakult'], function(){
            //getAllByLanguage +
            Route::get('/all/{language}', [FakultController::class ,'getAllByLanguage']);
        });
        //Group
        Route::group(['prefix'=>'group'], function(){
            //getAll +
            Route::post('/all', [GroupController::class ,'getAll']);
        });
    });
