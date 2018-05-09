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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::view('/', 'welcome', [
    'route' => 0,
    'campaigns' => App\Campaign::limit(8)->get(),
]);

// EXPLORE ----------------------------------------------------------------------------------------------
Route::view('/explore', 'explore', [
    'route' => 1,
    'campaigns' => App\Campaign::get(),
]);
Route::get('/explore/{campaign}', function (App\Campaign $campaign) {
    return view('campaign', [
        'route' => 1,
        'campaign' => $campaign,
    ]);
})->name('campaign');

// PROFILE ----------------------------------------------------------------------------------------------
Route::get('/profile/{worker}', function (App\Worker $worker) {
    $skills = false;
    if (Auth::user() && $worker && $worker->id == Auth::user()->id) $skills = \App\Skill::all();
    return view('profile', [
        'route' => 2,
        'worker' => $worker,
        'skills' => $skills
    ]);
})->name('profile');
Route::post('/profile/edit', function (Request $request) {
    $worker_id = $request->input("worker_id");
    $updateValues = $request->only([
        'name',
        'surname',
        'birthdate'
    ]);
    \App\Worker::find($worker_id)->update($updateValues);
    return redirect()->route('profile', ['worker' => $worker_id]);
});
Route::view('/profile', 'profile', [
    'route' => 2
]);

// CAMPAIGNS ----------------------------------------------------------------------------------------------
Route::post('join', function (Request $request) {
    $worker_id = $request->input("worker_id");
    $campaign_id = $request->input("campaign_id");
    \App\Worker::find($worker_id)->joined()->attach($campaign_id);
    return redirect()->route('campaign', ['campaign' => $campaign_id]);
})->name('join');

Auth::routes();
// Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
// Route::post('login', 'Auth\LoginController@login');
// Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// Route::post('register', 'Auth\RegisterController@register');

// // Password Reset Routes...
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('home');

