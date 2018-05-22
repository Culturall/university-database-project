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
                <li class="list-group-item">required workers: {{$campaign->required_workers}}</li>
                <li class="list-group-item">threshold percentage: {{$campaign->threshold_percentage}}%</li>
            </ul>
        </div>
    </div>
</div>

@auth @if (count($campaign->joiners()->where('worker', Auth::user()->id)))
<div class="btn-group btn-group-lg" role="group" aria-label="...">
    <form method="POST" action="{{ route('join') }}">
        @csrf @method('POST')
        <input type="hidden" name="worker_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
        <button type="submit" class="btn btn-primary">Join</button>
    </form>
</div>
@else
<div class="alert alert-info" role="alert">
    Already joined!
</div>
@endif @endauth @isset($campaign)
<h4 class="text-muted mt-4">tasks</h4>
<div class="row campaigns">
    @forelse ($campaign->tasks as $task)
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
        <p class="text-muted">No tasks yet
            <p>
    </div>
    @endforelse
</div>
@endisset
@endsection