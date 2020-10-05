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

Route::get('/', 'HomeController@index');
Auth::routes();
// Route::get('/', 'PageController@home');
// Route::post('/accesscode', 'PageController@postAccessCode');
//Route::get('/test', 'PageController@test');
Route::middleware(['auth'])->group(function () {
Route::get('/export', 'ExportController@exportOrders');
Route::get('/export/{email?}', 'ExportController@exportOrders');

Route::get('/products', 'ProductController@index');
Route::get('/products/trashed', 'ProductController@getTrashed');
Route::get('/products/{type}', 'ProductController@getProductsByType');
Route::resource('product', 'ProductController');
Route::get('product/personaliser/{productId}/{row_id_to_update}/{this_g3d_id}/{printJobRef?}', 'ProductController@personaliser');
Route::get('productepa/{id?}', 'ProductController@getExternalPricingAPI');

Route::get('basket', 'CartController@index');
Route::post('basket/add/{row_id_to_update?}/{next_g3d_id?}/{print_job_id_to_update?}','CartController@add');
Route::get('basket/destroy', 'CartController@destroy');
Route::get('basket/redir/{id?}/{gatewaymultiId?}', 'CartController@gatewayRedir');
Route::get('basket/remove-item/{rowId}', 'CartController@getRemoveItem');
Route::post('basket/update-qty/{rowId}', 'CartController@postUpdateQty');
Route::post('basket/post-to-print', 'CartController@postToPrint');
Route::get('complete', 'CartController@getComplete');

Route::get('/orders/{id}', 'OrderController@getOrders');
Route::get('/order/{id}', 'OrderController@getOrder');
Route::get('/logout', 'HomeController@logout');

});
