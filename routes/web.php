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

// Front-end
Route::group(['namespace' => 'App'], function() {
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/page/{page}', 'HomeController@home');
    Route::get('/post/{flag}', 'HomeController@posts')->name('post');
    Route::get('/tag/{flag}', 'HomeController@tags')->name('tag');
    Route::get('/archive/{year?}', 'HomeController@archive')->name('archive');
    Route::get('/about', 'HomeController@about');
    Route::get('/gallery', 'HomeController@gallery');
    Route::get('/feed', 'HomeController@feed');
    Route::get('/sitemap.xml', 'HomeController@siteMap');
});

// Back-end login
Route::group(['prefix' => 'avalon', 'namespace' => 'Backend'], function () {
    Route::get('/login', 'AuthController@showLogin')->name('admin.showLogin');
    Route::post('/auth/check', 'AuthController@check')->name('admin.login_check');
    Route::post('/auth/logout', 'AuthController@logout')->name('admin.logout');
    Route::post('/auth/login', 'AuthController@authenticate')->name('admin.login');
});

// Back-end with middleware
Route::group(['prefix' => 'avalon', 'middleware' => 'auth', 'namespace' => 'Backend'], function() {
    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
    Route::get('/dashboard/meta', 'DashboardController@meta');
    Route::get('/dashboard/refreshSetting', 'DashboardController@refreshSetting')->name('refreshSetting');
    Route::resource('/posts', 'PostsController');
    Route::get('/posts/changeState/{id}', 'PostsController@changeState');
    Route::get('/posts/publish/{id}', 'PostsController@publish');
    Route::resource('/tags', 'TagsController');
    Route::post('/links/store', 'LinksController@store');
    Route::post('/links/update', 'LinksController@update');
    Route::post('/links/destroy/{id}', 'LinksController@destroy');
    Route::get('/settings', 'SettingsController@settings');
    Route::post('/options/update', 'OptionsController@update');
    Route::post('/options/store', 'OptionsController@store');
    Route::post('/options/updateExt', 'OptionsController@updateExt');
    Route::get('/profile', 'ProfileController@profile');
    Route::post('/profile/resetPassword', 'ProfileController@resetPassword')->name('profile.resetPassword');
    Route::post('/profile/resetEmail', 'ProfileController@resetEmail')->name('profile.resetEmail');
    Route::post('/profile/resetUsername', 'ProfileController@resetUsername')->name('profile.resetUsername');
    Route::resource('/trash', 'TrashController');
    Route::post('/trash/restore/{id}', 'TrashController@restore');
    Route::resource('/file', 'FileController');
    Route::post('/file/destroy', 'FileController@destroy');
    Route::post('/file/getMore', 'FileController@getMore');
    Route::post('/file/uploadLogo', 'FileController@uploadLogo');
});
