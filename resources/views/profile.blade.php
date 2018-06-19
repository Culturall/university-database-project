@extends('app') 
@section('title', '') 
@section('content')
<div class="row">
    <div class="col-lg-9">
        <h1 class="display-1">{{$worker->name . ' ' . $worker->surname}}
        </h1>
        @if ($worker->requester)
        <span class="badge badge-primary mb-3" style="font-size: initial;">requester</span> @endif 
        @auth @if ($worker->id == Auth::user()->id)
            @if (!Auth::user()->requester)
                <a href="{{ route('profile.report', $worker->id) }}" class="btn btn-info mb-4 mt-4" role="button">Report</a>
            @endif
        <button type="button" class="badge btn btn-default float-right profile-edit-show mt-4 mb-4" style="font-size: initial;">Edit your profile</button>
        <form id="profile-edit-form" method="POST" action="{{ route('profile.edit', $worker->id) }}" class="mb-4" style="display: none;">
            @csrf @method('POST')

            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>

                <div class="col-md-9">
                    <input id="name" type="text" class="form-control" name="name" value="{{$worker->name}}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="surname" class="col-md-3 col-form-label text-md-right">Surname</label>

                <div class="col-md-9">
                    <input id="surname" type="text" class="form-control" name="surname" value="{{$worker->surname}}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="birthdate" class="col-md-3 col-form-label text-md-right">Birthdate</label>

                <div class="col-md-9">
                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy/mm/dd">
                        <input type="text" class="form-control" name="birthdate" value="{{$worker->birthdate}}">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>

                    @if ($errors->has('birthdate'))
                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('birthdate') }}</strong>
                                    </span> @endif
                </div>
            </div>

            <div class="form-group row mb-4">
                <div class="col-lg-12">
                    <button type="submit" class="badge btn btn-primary float-right" style="font-size: initial;">Edit</button>
                    <button type="button" class="badge btn btn-default float-right mr-2 cancel" style="font-size: initial;">Cancel</button>
                </div>
            </div>
        </form>
        @endif @endauth
    </div>
    @if (!$worker->requester)
    <div class="col-lg-3">
        <div class="card">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">score: {{$worker->score}}</li>
                <li class="list-group-item">skills:
                    {{ implode(', ', array_map(function ($e) { return $e['name']; }, $worker->skills->toArray())) ? : '-' }}
                </li>
            </ul>
        </div>
    </div>
    @endif
</div>

<h4 class="text-muted mt-4">campaigns</h4>

<div class="row campaigns">
    @if (!$worker->requester)
        @forelse ($worker->joined as $campaign)
        <div class="card-container col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $campaign->title }}</h5>
                    <p class="card-text text-truncate">{{ $campaign->description }}</p>
                    <a href="{{URL::to('/')}}/explore/{{$campaign->id}}" class="btn btn-outline-primary btn-sm">See more</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-centered">
            <p class="text-muted">Nothing
                <p>
        </div>
        @endforelse
    @else
        @forelse ($worker->campaigns as $campaign)
        <div class="card-container col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $campaign->title }}</h5>
                    <p class="card-text text-truncate">{{ $campaign->description }}</p>
                    <a href="{{URL::to('/')}}/explore/{{$campaign->id}}" class="btn btn-outline-primary btn-sm">See more</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-centered">
            <p class="text-muted">Nothing
                <p>
        </div>
        @endforelse
    @endif
</div>

@if (!$worker->requester)
<h4 class="text-muted">tasks</h4>

<div class="row campaigns">
    <?php $i = 0; ?>
    @forelse ($worker->getSelected() as $answer)
    <div class="card-container col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $answer->title }}</h5>
                <p class="card-text text-truncate">{{ $answer->description }}</p>
                <p class="small">{{ $answer->answer }}</p>
            </div>
        </div>
    </div>
    @empty
    <div class="col-centered">
        <p class="text-muted">Nothing<p>
    </div>
    @endforelse
</div>
@endif
@endsection