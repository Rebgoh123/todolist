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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::apiResources([
    'category' => 'CategoryController',
    'todolist' => 'TodolistController'
]);

Route::get('/todolist/getfile/{id}', 'TodolistController@getBlobFile');
Route::post('/todolist/uploadfile/{id}', 'TodolistController@storeFile');
Route::post('/todolist/checked/{id}', 'TodolistController@checked');



