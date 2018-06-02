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
Route::get('/explore', function (Request $request) {
    $batch = 5;
    $page = $request->input('page') ? (is_numeric($request->input('page')) ? intval($request->input('page')) : 1)  : 1;
    $pages = intval(App\Campaign::get()->count() / $batch);
    if ($page > $pages || $page < 1) {
        $page = 1;
    }
    return view('explore', [
        'route' => 1,
        'campaigns' => App\Campaign::limit($batch)->offset($page * $batch)->get(),
        'page' => $page ? : 1,
        'next' => $page && $page < $pages ? $page + 1 : null,
        'prev' => $page && $page > 1 ? $page - 1 : null
    ]);
})->name('explore');
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
        'birthdate',
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
    'route' => 1,
    'requester' => 8,
])->name('campaign.create');
Route::post('campaign/create', function (Request $request) {
    $data = $request->except(['worker_id', '_token', '_method']);
    $data['creator'] = $request->input("worker_id");
    if ($request->input('opening_date') > $request->input('closing_date') ||
        $request->input('sign_in_period_open') > $request->input('sign_in_period_close') ||
        $request->input('closing_date') < $request->input('sign_in_period_close')) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('date', 'There\'s an error with the given dates. Please try valid periods!');
        return redirect()->route('campaign.create')->withErrors($validator);
    }
    if (!is_numeric($request->input('required_workers')) || $request->input('required_workers') < 1) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('required_workers', 'Invalid number for required workers (' . $request->input('required_workers') . ')');
        return redirect()->route('campaign.create')->withErrors($validator);
    }
    try {
        DB::beginTransaction();
        $campaign = \App\Campaign::create($data);
        DB::commit();
    } catch (\Illuminate\Database\QueryException $ex) {
        DB::rollBack();
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', $ex->getMessage());
        return redirect()->route('campaign.create')->withErrors($validator);
    }
    return redirect()->route('campaign', ['campaign' => $campaign->id]);
})->name('campaign.create.action');
Route::get('campaign/{campaign}/edit', function (App\Campaign $campaign) {
    return view('campaign-edit', [
        'route' => 1,
        'campaign' => $campaign,
        'skills' => \App\Skill::all(),
    ]);
})->name('campaign.edit');
Route::post('campaign/create/task', function (Request $request) {
    $data = $request->except(['skills', 'options', '_token', '_method']);
    $options = json_decode($request->input('options'));
    $skills = $request->input('skills');
    if (count($options) < 2) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('options', 'Needs at least 2 options, ' . count($options) . ' given');
        return redirect()->route('campaign.edit', ['campaign' => $request->input('campaign')])->withErrors($validator);
    }
    $count_options = array_count_values($options);
    foreach ($count_options as $key => $value) {
        if ($value > 1) {
            $validator = Validator::make($request->all(), []);
            $validator->errors()->add('options', 'options should be different (' . $key . ')');
            return redirect()->route('campaign.edit', ['campaign' => $request->input('campaign')])->withErrors($validator);
        }
    }
    try {
        DB::beginTransaction();
        $task = \App\Task::create($data);
        foreach ($options as $option) {
            \App\TaskOption::create([
                'name' => $option,
                'task' => $task->id,
            ]);
        }
        foreach ($skills as $skill) {
            $task->needs()->attach($skill);
        }
        DB::commit();
    } catch (\Illuminate\Database\QueryException $ex) {
        DB::rollBack();
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', $ex->getMessage());
        return redirect()->route('campaign.edit', ['campaign' => $request->input('campaign')])->withErrors($validator);
    }
    return redirect()->route('campaign', ['campaign' => $request->input('campaign')]);
})->name('campaign.create.task.action');

// TASKS -------------------------------------------------------------------------------------------------
Route::get('task/{task}', function (App\Task $task) {
    if (Auth::user()->requester || !Auth::user()->joined()->where('campaign', $task->partOf->id)->count()) {
        $task = null;
    }
    return view('task', [
        'route' => 1,
        'task' => $task,
    ]);
})->name('task');
Route::post('task/answer', function (Request $request) {

})->name('answer.task.action');

// AUTH ----------------------------------------------------------------------------------------------
Auth::routes();
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

// Route::get('/home', 'HomeController@index')->name('home');