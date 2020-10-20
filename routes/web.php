<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home1');


Route::middleware(['auth.shopify'])->group(function () {

    Route::get('/', 'ShopifyTriggerController@index')->name('home');
    Route::get('trigger/createview', 'ShopifyTriggerController@createview')->name('triggers.createview');
    Route::post('/triggers/edit', 'ShopifyTriggerController@edit')->name('triggers.edit');
    Route::post('/triggers', 'ShopifyTriggerController@store')->name('triggers.store');
    Route::delete('/triggers/{id}', 'ShopifyTriggerController@destroy')->name('triggers.destroy');
    Route::put('/triggers/{id}', 'ShopifyTriggerController@update')->name('triggers.update');

    Route::post('handwrytten/login', 'HandwryttenApiController@login')->name('handwrytten.login');
    Route::get('handwrytten/edit/{id}', 'HandwryttenApiController@edit')->name('handwrytten.edit');
    Route::put('handwrytten/update/{id}', 'HandwryttenApiController@update')->name('handwrytten.update');
    Route::delete('handwrytten/destroy/{id}', 'HandwryttenApiController@destroy')->name('handwrytten.destroy');
    Route::post('handwrytten/logout/{id}', 'HandwryttenApiController@logout')->name('handwrytten.logout');
    

    Route::get('/configuration', 'HandwryttenApiController@config');

    Route::post('ajaxRequest', 'HandwryttenApiController@cardData')->name('ajaxRequest.post');
   // Route::get('shopify', 'ShopifyController@index')->name('get.records');
   
   Route::get('handwrytten/view', 'HandwryttenApiController@view')->name('handwrytten.view');
});

