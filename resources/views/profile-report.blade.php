@extends('app') 
@section('title', '') 
@section('content')
<div class="row">
    <div class="col-lg-9">
        <h1 class="display-1">{{$worker->name . ' ' . $worker->surname}}
        </h1>
        @if ($worker->requester)
        <span class="badge badge-primary mb-3" style="font-size: initial;">requester</span> @endif 
        <a href="{{ route('profile', $worker->id) }}" class="btn btn-info mt-4 mb-4" role="button">back</a>
    </div>
    @if (!$worker->requester)
    <div class="col-lg-3">
        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">score: {{$worker->score}}</li>
                <li class="list-group-item">skills:
                    {{ implode(', ', array_map(function ($e) { return $e['name']; }, $worker->skills->toArray())) }}
                </li>
            </ul>
        </div>
    </div>
    @endif
</div>

<h3 class="text-muted mt-4">results</h3>

@foreach ($results as $result)
    <h5 class="text-muted mt-4">{{ $result['campaign']->title }}</h5>
    <span class="badge badge-primary badge-pill">{{ $result['position'] }}</span>
    <div class="row campaigns">
        @forelse ($worker->getSelectedByCampaign($result['campaign']->id) as $task)
            <div class="card-container col-sm-6 col-lg-3">
            <div class="card">
                    <div class="card-body
                        {{ $task->validity ? ($worker->getSelectedByTask($task->id)->id == $task->answer()->id ? 'text-success' : 'text-warning') : '' }}">
                        <h5 class="card-title">{{ $task->title }}</h5>
                        <p class="card-text">{{ $task->description }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-centered">
                <p class="text-muted">Nothing
                    <p>
            </div>
        @endforelse
    </div>
@endforeach

@endsection