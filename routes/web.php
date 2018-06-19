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
use Illuminate\Support\Facades\DB;

Route::redirect('/', '/welcome', 301);
Route::get('/welcome', function (Request $request) {
    return view('welcome', [
        'route' => 0,
        'campaigns' => App\Campaign::limit(8)->get(),
    ]);
})->name('welcome');

// EXPLORE ----------------------------------------------------------------------------------------------
Route::get('/explore', function (Request $request) {
    $search = $request->input('search');
    $batch = 10;
    $page = $request->input('page') ? (is_numeric($request->input('page')) ? intval($request->input('page')) : 1) : 1;

    $query = App\Campaign::query();
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    $pages = intval($query->count() / $batch) + 1;
    if ($page > $pages || $page < 1) {
        $page = 1;
    }
    $query->limit($batch)->offset(($page - 1) * $batch);

    return view('explore', [
        'route' => 1,
        'campaigns' => $query->get(),
        'page' => $page ?: 1,
        'next' => $page && $page < $pages ? $page + 1 : null,
        'prev' => $page && $page > 1 ? $page - 1 : null,
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
    if (!Auth::user()) {
        return redirect('welcome');
    }

    return view('profile', [
        'route' => 2,
        'worker' => $worker,
    ]);
})->name('profile');
Route::get('/profile/{worker}/report', function (App\Worker $worker) {
    if (!Auth::user() || Auth::user()->requester || Auth::user()->pending || $worker->id != Auth::user()->id) {
        return redirect()->route('profile', ['worker' => $worker->id]);
    }

    $results = [];
    foreach ($worker->joined as $campaign) {
        $leaderboard = DB::select(DB::raw('select * from get_campaign_leaderboard(' . $campaign->id . ')'));

        if ($leaderboard) {
            $i = 1;
            foreach ($leaderboard as $result) {
                if ($worker->id == $result->get_campaign_leaderboard) {
                    $position = $i;
                    break;
                }

                $i++;
            }
        }

        $results[] = [
            'campaign' => $campaign,
            'position' => isset($position) ? $position : '-',
        ];
    }

    return view('profile-report', [
        'route' => 2,
        'worker' => $worker,
        'results' => $results,
    ]);
})->name('profile.report');
Route::post('/profile/{worker}/edit', function (App\Worker $worker, Request $request) {

    if (!Auth::user() || $worker->id != Auth::user()->id) {
        return redirect()->route('profile', ['worker' => $worker->id]);
    }

    $updateValues = $request->only([
        'name',
        'surname',
        'birthdate',
    ]);
    \App\Worker::find($worker->id)->update($updateValues);
    return redirect()->route('profile', ['worker' => $worker->id]);
})->name('profile.edit');

// CAMPAIGNS ----------------------------------------------------------------------------------------------
Route::post('join', function (Request $request) {
    $worker_id = $request->input("worker_id");
    $campaign_id = $request->input("campaign_id");
    if (!Auth::user() || Auth::user()->id != $worker_id || Auth::user()->pending || Auth::user()->requester) {
        return redirect()->route('campaign', ['campaign' => $campaign_id]);
    }
    \App\Worker::find($worker_id)->joined()->attach($campaign_id);
    return redirect()->route('campaign', ['campaign' => $campaign_id]);
})->name('join');
Route::get('campaign/create', function (Request $request) {
    if (!Auth::user() || !Auth::user()->requester) {
        return redirect()->route('welcome');
    }


    return view('campaign-create', [
        'route' => 1,
        'requester' => 8]
    );
})->name('campaign.create');
Route::post('campaign/create', function (Request $request) {
    $data = $request->except(['worker_id', '_token', '_method']);
    $data['creator'] = $request->input("worker_id");

    if (!Auth::user() || Auth::user()->id != $data['creator'] || !Auth::user()->requester) {
        return redirect()->route('welcome');
    }

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
    if (!Auth::user() || !Auth::user()->requester || Auth::user()->id != $campaign->creator) {
        return redirect()->route('campaign', ['campaign' => $campaign_id]);
    }

    return view('campaign-edit', [
        'route' => 1,
        'campaign' => $campaign,
        'skills' => \App\Skill::all(),
    ]);
})->name('campaign.edit');
Route::get('campaign/{campaign}/report', function (App\Campaign $campaign) {
    if (Auth::user() && Auth::user()->requester && Auth::user()->id == $campaign->creator) {
        $topten = DB::select(DB::raw('select * from get_campaign_top_ten(' . $campaign->id . ')'));
        $workers = [];
        if ($topten) {
            foreach ($topten as $result) {
                $workers[] = \App\Worker::find($result->get_campaign_top_ten);
            }
        }

        return view('campaign-report', [
            'route' => 1,
            'campaign' => $campaign,
            'topten' => $workers,
        ]);
    }

    return redirect()->route('campaign', ['campaign' => $campaign->id]);
})->name('campaign.report');
Route::post('campaign/{campaign}/create/task', function (App\Campaign $campaign, Request $request) {
    if (!Auth::user() || !Auth::user()->requester || Auth::user()->id != $campaign->creator) {
        return redirect()->route('campaign', ['campaign' => $campaign->id]);
    }

    $data = $request->except(['skills', 'options', '_token', '_method']);
    $data['campaign'] = $campaign->id;
    $options = json_decode($request->input('options'));
    $skills = $request->input('skills');
    if (count($options) < 2) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('options', 'Needs at least 2 options, ' . count($options) . ' given');
        return redirect()->route('campaign.edit', ['campaign' => $campaign->id])->withErrors($validator);
    }
    $count_options = array_count_values($options);
    foreach ($count_options as $key => $value) {
        if ($value > 1) {
            $validator = Validator::make($request->all(), []);
            $validator->errors()->add('options', 'options should be different (' . $key . ')');
            return redirect()->route('campaign.edit', ['campaign' => $campaign->id])->withErrors($validator);
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
        return redirect()->route('campaign.edit', ['campaign' => $campaign->id])->withErrors($validator);
    }
    return redirect()->route('campaign', ['campaign' => $campaign->id]);
})->name('campaign.create.task.action');

// TASKS -------------------------------------------------------------------------------------------------
Route::get('task/{task}', function (App\Task $task, Request $request) {

    if (!Auth::user() || Auth::user()->requester || Auth::user()->pending || !Auth::user()->joined()->where('campaign', $task->partOf->id)->count()) {
        $task = null;
    }

    if (!checkAssigned($request, $task)) {
        $task = null;
    }

    return view('task', [
        'route' => 1,
        'task' => $task,
    ]);
})->name('task');
Route::post('task/assign', function (Request $request) {
    if (!Auth::user() || Auth::user()->requester || Auth::user()->pending) {
        return redirect()->route('welcome');
    }

    $task = DB::select(DB::raw('select * from gettask(' . Auth::user()->id . ')'));
    
    if (is_array($task) && isset($task[0]) && isset($task[0]->gettask)) {
        $task = $task[0]->gettask;
    } else {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', 'There are no tasks for you atm, try joining a campaign');
        return redirect()->route('welcome')->withErrors($validator);
    }
    $request->session()->put('assigned', Auth::user()->id . ':' . $task);
    
    return redirect()->route('task', ['task' => $task]);
})->name('task.assign');
Route::post('task/answer', function (Request $request) {
    if (!Auth::user() || Auth::user()->requester || Auth::user()->pending) {
        return redirect()->route('welcome');
    }

    if (!$request->filled('task') || !$request->filled('option')) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', 'There was an error handling your request, please retry');
        return redirect('/welcome')->withErrors($validator);
    }

    if (!checkAssigned($request, \App\Task::find($request->input('task')))) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', 'You\'re not authorized to answer this task');
        return redirect('/')->withErrors($validator);
    }

    $request->session()->forget('assigned');
    Auth::user()->selected()->attach($request->input('option'));
    return redirect('/');
})->name('answer.task.action');
Route::get('task/{task}/remove', function (App\Task $task, Request $request) {
    if (!Auth::user() || !Auth::user()->requester || Auth::user()->id != $task->partOf->creator) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', 'You\'re not the requester of this task');
        return redirect()->route('welcome')->withErrors($validator);
    }

    if ($task->validity) {
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', 'Task has been flagged as valid, can\'t delete it now');
        return redirect()->route('campaign.edit', ['campaign' => $task->partOf->id])->withErrors($validator);
    }

    try {
        DB::beginTransaction();
        $task->delete();
        DB::commit();
    } catch (\Illuminate\Database\QueryException $ex) {
        DB::rollBack();
        $validator = Validator::make($request->all(), []);
        $validator->errors()->add('exception', $ex->getMessage());
        return redirect()->route('campaign.edit', ['campaign' => $task->partOf->id])->withErrors($validator);
    }

    return redirect()->route('campaign.edit', ['campaign' => $task->partOf->id]);
})->name('task.remove');

// ADMIN -------------------------------------------------------------------------------------------------
Route::get('admin/panel', function (Request $request) {

    if (!Auth::user() || !Auth::user()->admin) {
        return redirect()->route('welcome');
    }

    return view('admin-panel', [
        'route' => 3,
        'workers' => App\Worker::where('pending', true)->get(),
        'admins' => App\Worker::where('admin', true)->get()
    ]);
})->name('admin');
Route::get('admin/promote', function (Request $request) {
    if (!Auth::user() || !Auth::user()->admin) {
        return redirect()->route('welcome');
    }

    $worker = $request->input('worker');
    $worker = \App\Worker::find($worker);
    $worker->update([
        'pending' => false,
        'requester' => true
    ]);
    return redirect()->route('admin');
})->name('admin.promote');

// AUTH ----------------------------------------------------------------------------------------------
Auth::routes();
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');

function checkAssigned(Request $request, App\Task $task) {
    if ($request->session()->has('assigned')) {
        $assigned = explode(':', $request->session()->get('assigned'));
        if ((int) $assigned[0] != Auth::user()->id || $task->id != (int) $assigned[1]) {
            return false;
        }
        return true;
    } else {
        return false;
    }
}

// Route::get('/home', 'HomeController@index')->name('home');