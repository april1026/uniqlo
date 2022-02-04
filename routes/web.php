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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@index')->name('products.index');
    Route::get('/stockouts', 'ProductController@stockouts')->name('products.stockouts');
    Route::get('/sales', 'ProductController@sales')->name('products.sales');
    Route::get('/limited-offers', 'ProductController@limitedOffers')->name('products.limited-offers');
    Route::get('/multi-buys', 'ProductController@multiBuys')->name('products.multi-buys');
    Route::get('/news', 'ProductController@news')->name('products.news');
    Route::get('/go', 'ProductController@go')->name('products.go');
    Route::get('/most-reviewed', 'ProductController@mostReviewed')->name('products.most-reviewed');
    Route::get('/{product}', 'ProductController@show')->name('products.show');
});

Route::group(['prefix' => 'hmall-products'], function () {
    Route::get('/{hmallProduct:product_code}', 'HmallProductController@show')->name('hmall-products.show');
});

Route::group(['prefix' => 'search'], function () {
    Route::get('/', 'SearchController@index')->name('search.index');
    Route::get('/{query}', 'SearchController@show')->name('search.show');
});

Route::group(['prefix' => 'pages'], function () {
    Route::get('/changelog', 'PageController@getChangelog')->name('pages.changelog');
    Route::get('/privacy', 'PageController@getPrivacyPolicy')->name('pages.privacy-policy');
});

Route::group(['prefix' => 'lists'], function () {
    Route::get('/limited-offers', 'ListController@getLimitedOffers')->name('lists.limited-offers');
    Route::get('/sale', 'ListController@getSale')->name('lists.sale');
    Route::get('/most-reviewed', 'ListController@getMostReviewed')->name('lists.most-reviewed');
    Route::get('/new', 'ListController@getNew')->name('lists.new');
    Route::get('/coming-soon', 'ListController@getComingSoon')->name('lists.coming-soon');
    Route::get('/multi-buy', 'ListController@getMultiBuy')->name('lists.multi-buy');
});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
