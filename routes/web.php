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

Route::get('/', 'ReportsController@index')->name('index');

Route::get('/report/{id}', 'ReportsController@showById');

Route::get('/report/{id}/change', 'ReportsController@showByIdChange');

Route::post('/modify/add', 'ReportsModifyController@addNew');

Route::post('/modify/update', 'ReportsModifyController@update');

Route::post('/modify/delete', 'ReportsModifyController@delete');

Route::get('/user/{url}', 'UsersController@showUser');

Route::get('/user/get/{userName}', 'UsersController@getUser');

Route::post('/user/update/name', 'UsersController@updateName');

Route::post('/user/update/email', 'UsersController@updateEmail');

Route::post('/user/update/url', 'UsersController@updateUrl');

Route::post('/user/modify/delete', 'UsersController@deleteUser');

Route::get('/url/exists/{url}', 'UsersController@getUrl');

Route::post('/search', 'SearchController@index');

Route::get('/search/{tag?}', 'SearchController@tag');

Route::get('/fake/create/{count}', 'FakesController@make');

Route::get('/search/tagName/{tag?}', 'SearchController@tagByName');

Route::get('/tags/all', 'TagController@getAll');
