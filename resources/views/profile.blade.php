@extends('app')

@section('title', '')

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <h1 class="display-1">{{$worker->name . ' ' . $worker->surname}}
                @if ($worker->requester) 
                    <span class="badge badge-primary" style="font-size: initial; vertical-align: top;">requester</span>
                @endif
            </h1>
            <h1 class="display-4 text-muted">working on</h5>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <ul class="list-group list-group-flush">
                    @forelse ($worker->skills as $skill)
                        <li class="list-group-item">{{$skill->name}}: {{$skill->pivot->value}}</li>
                    @empty
                        <li class="list-group-item">no skills</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="row campaigns">
        @forelse ($worker->joined as $campaign)
            <div class="card-container col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $campaign->title }}</h5>
                        <p class="card-text">{{ $campaign->description }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-centered">
                <p class="text-muted">Nothing<p>
            </div>
        @endforelse
    </div>

    <h1 class="display-4 text-muted">working on</h5>
    
@endsection