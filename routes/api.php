<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout-user', 'API\UserController@logout');
    Route::get('/sora/videos', 'API\SoraVideoController@index');
    Route::post('/sora/generate', 'API\SoraVideoController@generate');
    
});
// ------------------------------------------- APi Service Request --------------------------------------- //
Route::post('/contacts-query', 'API\UserController@contacts');
Route::post('/subscribe-newslatter', 'API\UserController@subscribenewslatter');

Route::get('/all-packages', 'API\PackagesController@AllPackages');



Route::post('/login-user', 'API\UserController@userlogin');
Route::post('/signup-user', 'API\UserController@sendmail');
Route::post('/resend-otp', 'API\UserController@resendOtp');

Route::post('/forget-otp', 'API\UserController@sendotp');
Route::post('/verify-otp', 'API\UserController@forgetverifyotp');
Route::post('/change-password', 'API\UserController@chnagepassword');


Route::post('/verify-user', 'API\UserController@verifymail');

Route::get('/all-blogs', 'API\BlogController@allBlogs');
Route::get('/blog/{slug}', 'API\BlogController@blogdetail');


Route::post('/blog/store', 'API\BlogController@storeblog');


