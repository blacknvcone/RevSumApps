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

Route::get('/','CompaniesController@index')->name('listCompany');
Route::get('/companies/{id}','CompaniesController@show')->name('detailCompany');
Route::post('/companies/update','CompaniesController@update')->name('updateCompany');