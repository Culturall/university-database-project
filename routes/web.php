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
use Illuminate\Http\Request;

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
    $skills = false;
    if (Auth::user() && $worker && $worker->id == Auth::user()->id) $skills = \App\Skill::all();
    return view('profile', [
        'route' => 2,
        'css_files' => ['welcome'],
        'worker' => $worker,
        'skills' => $skills
    ]);
})->name('profile');
Route::post('/profile/edit', function (Request $request) {
    $worker_id = $request->input("worker_id");
    \App\Worker::find(54)->update(["name" => "PAOLOOO"]);
    return redirect()->route('profile', ['worker' => $worker_id]);
});

Route::view('/profile', 'profile', [
    'route' => 2
]);

// Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'HomeController@index')->name('home');
