<?php

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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
	Route::group([ 'prefix' => 'user'], function () {
	    Route::resource('access-users', 'UserBundle\AccessUserController');
	    Route::resource('menu', 'UserBundle\MenuController');
	    Route::resource('sub-menu', 'UserBundle\SubMenuController');
	    Route::resource('navi', 'UserBundle\NavigationController');
	    Route::resource('profile', 'UserBundle\ProfileController');

	});
});

Route::group(['middleware' => 'auth', 'prefix' => 'api'], function () {
	Route::group([ 'prefix' => 'users'], function () {
	    Route::post('menu/add', 'UserBundle\AccessUserRoleController@add'); 
	    Route::post('menu/remove', 'UserBundle\AccessUserRoleController@remove'); 

	});
 

});