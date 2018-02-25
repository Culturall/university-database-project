@extends('app')

@section('title', 'Explore')

@section('content')
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
    </form>
    @isset($campaigns)
        <div class="row campaigns">
            @foreach ($campaigns as $campaign)
                <div class="card-container col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->title }}</h5>
                            <p class="card-text">{{ $campaign->description }}</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">See more</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endisset
@endsection