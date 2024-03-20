<?php

use App\Http\Controllers\Api\v1\CommentLikeController;
use App\Http\Controllers\Api\v1\CommunityController;
use App\Http\Controllers\Api\v1\CommunityManagerController;
use App\Http\Controllers\Api\v1\ComplaintController;
use App\Http\Controllers\Api\v1\ConsumerController;
use App\Http\Controllers\Api\v1\LanguageController;
use App\Http\Controllers\Api\v1\PostLikeController;
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
    ->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
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
                Route::delete('/logout', [ConsumerController::class, 'logout']);
                Route::put('/update', [ConsumerController::class, 'update']);
                Route::delete('/delete', [ConsumerController::class, 'delete']);
            });
            //Community
            Route::group(['prefix' => 'community'], function(){
                Route::post('/create', [CommunityController::class, 'create']);
                Route::put('/update/{id}',  [CommunityController::class, 'update']);
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

        //Consumer
        Route::group(['prefix'=>'consumer'], function (){
           Route::post('/search', [ConsumerController::class, 'search']);
        });
        //Community
        Route::group(['prefix'=>'community'], function (){
            Route::post('/search', [CommunityController::class, 'search']);
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


    });
