@extends('app') 
@section('title', $campaign->title) 
@section('content')
<div class="row">
    <div class="col-lg-9">
        <h1 class="display-1">{{$campaign->title}}</h1>
        <h1 class="display-4 text-muted"><i>created by</i> <a href="{{ url('/') }}/profile/{{$campaign->createdBy->id}}">{{$campaign->createdBy->name}} {{$campaign->createdBy->surname}}</a></h5>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">from {{$campaign->opening_date}} to {{$campaign->closing_date}}</li>
                <li class="list-group-item">sign in from {{$campaign->sign_in_period_open}} to {{$campaign->sign_in_period_close}}</li>
                <li class="list-group-item">required workers: {{$campaign->required_workers}}</li>
                <li class="list-group-item">threshold percentage: {{$campaign->threshold_percentage}}%</li>
            </ul>
        </div>
    </div>
</div>

<a href="{{ route('campaign', $campaign->id) }}" class="btn btn-info mt-4" role="button">Back</a>

<h4 class="text-muted mt-4">% completed tasks</h4>
<p>{{ count($campaign->completedTasks) .  '/' . count($campaign->tasks) . ' = ' . count($campaign->completedTasks) * 100 / count($campaign->tasks) . '%' }}</p>

<h4 class="text-muted mt-4">Completed</h4>
<div class="row campaigns">
        @forelse ($campaign->completedTasks as $task)
            <div class="card-container col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text">
                            {{ $task->answer()->name }}
                        </p>
                    </div>
                </div>
            </div>
        @empty 
        <div class="col-centered">
                <p class="text-muted">No tasks at this state
                    <p>
            </div>
        @endforelse
</div>

<h4 class="text-muted mt-4">Working on</h4>
<div class="row campaigns">
    @forelse ($campaign->activeTasks() as $task)
    <div class="card-container col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text">
                    @if (strlen($task->description) > 300) {{ substr($task->description, 0, 300) }}&hellip; @else {{$task->description}} @endif
                </p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-centered">
        <p class="text-muted">No tasks at this state
            <p>
    </div>
    @endforelse
</div>

<h4 class="text-muted mt-4">Inactive</h4>
<div class="row campaigns">
    @forelse ($campaign->inactiveTasks() as $task)
    <div class="card-container col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text">
                    @if (strlen($task->description) > 300) {{ substr($task->description, 0, 300) }}&hellip; @else {{$task->description}} @endif
                </p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-centered">
        <p class="text-muted">No tasks at this state
            <p>
    </div>
    @endforelse
</div>

<h4 class="text-muted mt-4">Top ten</h4>
<div class="col-xs-12 col-lg-6">
<ul class="list-group">
    @forelse ($topten as $worker)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $worker->name }}
        <span class="badge  badge-primary badge-pill">{{ $loop->index + 1}}</span>
          </li>
    @empty
    <li class="list-group-item d-flex justify-content-between align-items-center">
            No completed tasks yet
          </li>
    @endforelse
</ul>
</div>
@endsection