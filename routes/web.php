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

Route::redirect('/', '/welcome', 301);
Route::view('/welcome', 'welcome', [
    'route' => 0,
    'campaigns' => App\Campaign::limit(8)->get(),
]);

// EXPLORE ----------------------------------------------------------------------------------------------
Route::view('/explore', 'explore', [
    'route' => 1,
    'campaigns' => App\Campaign::get(),
])->name('explore');
Route::get('/explore/{campaign}', function (App\Campaign $campaign) {
    return view('campaign', [
        'route' => 1,
        'campaign' => $campaign,
    ]);
})->name('campaign');

// PROFILE ----------------------------------------------------------------------------------------------
Route::get('/profile/{worker}', function (App\Worker $worker) {
    $skills = false;
    if (Auth::user() && $worker && $worker->id == Auth::user()->id) {
        $skills = \App\Skill::all();
    }

    return view('profile', [
        'route' => 2,
        'worker' => $worker,
        'skills' => $skills,
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
    'route' => 2,
]);

// CAMPAIGNS ----------------------------------------------------------------------------------------------
Route::post('join', function (Request $request) {
    $worker_id = $request->input("worker_id");
    $campaign_id = $request->input("campaign_id");
    \App\Worker::find($worker_id)->joined()->attach($campaign_id);
    return redirect()->route('campaign', ['campaign' => $campaign_id]);
})->name('join');
Route::view('campaign/create', 'campaign-create', [
    'requester' => 8,
])->name('campaign.create');
Route::post('campaign/create', function (Request $request) {
    $data = $request->input()->except('worker_id');
    $data['creator'] = $request->input("worker_id");
    $campaign = new \App\Campaign();
    $campaign->update($data);
    $campaign->save();
    return redirect()->route('campaign', ['campaign' => $campaign->id]);
})->name('campaign.create.action');

// AUTH ----------------------------------------------------------------------------------------------
Auth::routes();
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

// Route::get('/home', 'HomeController@index')->name('home');
