@extends('app')

@section('title', 'Welcome')

@section('content')
    <h1 class="display-1">Welcome to DB-project</h1>
    <h1 class="display-4 text-muted">A <i>work for tasks</i> place</h5>

    @if ($errors->has('exception'))
        <div class="alert alert-danger mt-4" role="alert">
            {{ $errors->first('exception') }}
        </div>
    @endif

    <div class="btn-group btn-group-lg" role="group" aria-label="...">
        @auth
            <form id="get-task-form" method="POST" action="{{ route('task.assign') }}">
                @csrf
                @method('POST')

                <input type="hidden" name="worker_id" value="{{Auth::user()->id}}">

                @if (!Auth::user()->requester)
                    <button class="btn btn-primary action" type="submit">Get a task</button>
                @else
                    <a class="btn btn-primary action" href="
                        {{ route('campaign.create') }}
                    ">Create a campaign</a>
                @endif
            </form>
        @endauth
    </div>

    @isset($campaigns)
        <div class="row campaigns">
            @foreach ($campaigns as $campaign)
                <div class="card-container col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->title }}
                                @if ($campaign->sign_in_period_open)
                                    @dateBetween($campaign->sign_in_period_open, $campaign->sign_in_period_close)
                                        <span class="badge badge-primary">Joinable</span>
                                    @enddateBetween
                                @endif
                                @dateBetween($campaign->opening_date, $campaign->closing_date)
                                    <span class="badge badge-success">Active</span>
                                @elseif (strtotime($campaign->closing_date) < strtotime(date('Y-m-d')))
                                    <span class="badge badge-secondary">Ended</span>
                                @elseif (strtotime(date('Y-m-d')) - strtotime($campaign->closing_date) <= 60 * 24 * 7)
                                    <span class="badge badge-info">Soon</span>
                                @endif
                            </h5>
                            <p class="card-text text-truncate">
                                {{$campaign->description}}
                            </p>
                            <p class="small">from {{$campaign->opening_date}} to {{$campaign->closing_date}}</p>
                            <a href="{{URL::to('/')}}/explore/{{$campaign->id}}" class="btn btn-outline-primary">See more</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endisset
@endsection