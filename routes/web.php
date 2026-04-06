<?php

use Illuminate\Support\Facades\Route;

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
// Route::get('/{any?}', function () {
//     return view('vuewelcome');
// })->where('any' , '.*');






Route::get('/login', 'HomeController@login')->name('login');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'HomeController@dashboard');
    //  ----------------- Currency Controller -----------------------------------//




    //  ----------------- Users Controller -----------------------------------//
    Route::get('/newusers/show', 'NewusersController@index')->name('newusers.index');
    Route::get('/newusers/create', 'NewusersController@create')->name('newusers.create');
    Route::post('/newusers/store', 'NewusersController@store')->name('newusers.store');
    Route::put('/newusers/update/{id}', 'NewusersController@update')->name('newusers.update');
    Route::get('/newusers/edit/{id}', 'NewusersController@edit')->name('newusers.edit');
    Route::get('/newusers/destroy/{id}', 'NewusersController@destroy')->name('newusers.destroy');

    Route::get('/user-search', 'NewusersController@searchUsers')->name('user.search');

    Route::get('/signout', 'HomeController@signout')->name('signout');

    //  ----------------- services Controller -----------------------------------//

    Route::get('/contact/us', 'ServicesController@contactus')->name('contactus.index');
    Route::get('/contact/us/{id}', 'ServicesController@contactusdel')->name('contactus.destroy');
    Route::get('/subscribe/newsletters', 'ServicesController@subscribe')->name('subscribe.index');
    Route::get('/subscribe/newsletters/{id}', 'ServicesController@subscribedel')->name('subscribe.destroy');
});
Route::post('/login_process', 'HomeController@login_process')->name('login_process');





Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
