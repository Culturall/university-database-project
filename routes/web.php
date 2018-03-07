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

Route::view('/', 'welcome', [
    'route' => 0,
    'css_files' => ['welcome'],
    'campaigns' => App\Campaign::limit(8)->get(),
]);
Route::view('/explore', 'explore', [
    'route' => 1,
    'css_files' => ['explore'],
    'campaigns' => App\Campaign::get(),
]);
Route::get('/explore/{campaign}', function (App\Campaign $campaign) {
    return view('campaign', [
        'route' => 1,
        'css_files' => ['welcome'],
        'campaign' => $campaign,
    ]);
});
Route::get('/profile/{worker}', function (App\Worker $worker) {
    return view('profile', [
        'route' => 2,
        'css_files' => ['welcome'],
        'worker' => $worker,
    ]);
});
Route::view('/profile', 'profile', [
    'route' => 2
]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
