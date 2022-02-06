<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'ProductController@index');

// Route::get('/coinbase-checkout', 'ProductController@checkout');

Route::post('/checkout_coinbase', 'ProductController@checkout')->name('checkout_coinbase');

Route::post('/update_coinbase', 'ProductController@update_product')->name('update_coinbase');

Route::post('/delete_coinbase', 'ProductController@delete_product')->name('delete_coinbase');

Route::get('/products', 'ProductController@products');
