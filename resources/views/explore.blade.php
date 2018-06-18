@extends('app') 
@section('title', 'Explore') 
@section('content')
<div class="col-lg-12 clearfix">
    <input id="searchfield" class="form-control mr-2" type="search" placeholder="looking for something" aria-label="looking for something" style="width: 200px; float: left;">
    <button id="search" class="btn btn-primary" style="float: left;">
        Search
    </button>
</div>
@isset($campaigns)
<div class="row campaigns">
    @foreach ($campaigns as $campaign)
    <div class="card-container col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    {{ $campaign->title }} @if ($campaign->sign_in_period_open) @dateBetween($campaign->sign_in_period_open, $campaign->sign_in_period_close)
                    <span class="badge badge-primary">Joinable</span> @enddateBetween @endif @dateBetween($campaign->opening_date,
                    $campaign->closing_date)
                    <span class="badge badge-success">Active</span> @else @if (strtotime($campaign->closing_date)
                    < strtotime(date( 'Y-m-d'))) <span class="badge badge-secondary">Ended</span>
                        @elseif (strtotime(date('Y-m-d')) - strtotime($campaign->opening_date)
                        <= 60 * 24 * 7) <span class="badge badge-info">Soon</span>
                            @endif @enddateBetween
                </h5>
                <p class="card-text">
                    @if (strlen($campaign->description) > 300) {{ substr($campaign->description, 0, 300) }}&hellip; @else {{$campaign->description}}
                    @endif
                </p>
                <p class="small">from {{$campaign->opening_date}} to {{$campaign->closing_date}}</p>
                <a href="{{URL::to('/')}}/explore/{{$campaign->id}}" class="btn btn-outline-primary btn-sm">See more</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div id="pagination" class="col-sm-12 mt-4">
    @if($prev)
        <a href="{{ route('explore') . '?page=' . $prev }}"><span><</span> prev</a>
    @endif
    <span class="ml-2 mr-2">{{$page}}</span>
    @if($next)
        <a href="{{ route('explore') . '?page=' . $next }}">next <span>></span></a>
    @endif
</div>
@endisset
@endsection