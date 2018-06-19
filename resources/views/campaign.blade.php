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
@auth 
@if (!Auth::user()->requester && !Auth::user()->pending)
    @dateBetween($campaign->sign_in_period_open, $campaign->sign_in_period_close)
        @if (!$campaign->joiners()->where('worker', Auth::user()->id)->count())
        <div class="btn-group btn-group-lg" role="group" aria-label="...">
            <form method="POST" action="{{ route('join') }}">
                @csrf @method('POST')
                <input type="hidden" name="worker_id" value="{{ Auth::user()->id }}">
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                <button type="submit" class="btn btn-primary">Join</button>
            </form>
        </div>
        @else
        <div class="alert alert-info mt-4" role="alert">
            Already joined!
        </div>
        @endif 
    @enddateBetween 
@endif 
@if (Auth::user()->id == $campaign->creator)
    @dateBetween($campaign->sign_in_period_open, $campaign->closing_date)
        <a href="{{ route('campaign.edit', $campaign->id) }}" class="btn btn-warning mt-4" role="button">Edit</a>
    @enddateBetween
        <a href="{{ route('campaign.report', $campaign->id) }}" class="btn btn-info mt-4" role="button">Report</a>
@endif @endauth

<h4 class="text-muted mt-4">Description</h4>
<p>{{$campaign->description}}</p>

<h4 class="text-muted mt-4">Completed</h4>
<div class="row campaigns">
        @forelse ($campaign->completedTasks as $task)
            <div class="card-container col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text text-truncate">
                            {{$task->description}}
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
                <p class="card-text text-truncate">
                    {{$task->description}}
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
                <p class="card-text text-truncate">
                    {{$task->description}}
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
@endsection