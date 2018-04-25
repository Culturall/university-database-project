@extends('app')

@section('title', '')

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <h1 class="display-1">{{$worker->name . ' ' . $worker->surname}}
            </h1>
            @if ($worker->requester) 
                <span class="badge badge-primary mb-3" style="font-size: initial;">requester</span>
            @endif
            @auth
                @if ($worker->id == Auth::user()->id)
                    <form  method="POST" action="{{ url('/') }}/profile/edit" class="mb-4">
                        @csrf
                        @method('POST')

                        <input type="hidden" name="worker_id" value="{{$worker->id}}">

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
                                <div class="input-group date" data-provide="datepicker">
                                    <input type="text" class="form-control" name="birthdate" value="{{$worker->birthdate}}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>

                                @if ($errors->has('birthdate'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('birthdate') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="skills" class="col-md-3 col-form-label text-md-right">Skills</label>
                            <div class="col-md-9">
                                @php
                                    $worker_skills_names = [];

                                    foreach($worker->skills as $skill) {
                                        array_push($worker_skills_names, $skill->name);
                                    }
                                @endphp
                                <select class="form-control form-control-sm mb-3" id="skill-1" name="skill-1">
                                    <option selected value="">-</option>
                                    @foreach ($skills as $skill)
                                        <option value="{{$skill->name}}" {{(count($worker_skills_names>=1) && $worker_skills_names[0] == $skill->name) ? 'selected' : ''}}>{{$skill->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control form-control-sm mb-3" id="skill-2" name="skill-2">
                                    <option selected value="">-</option>
                                    @foreach ($skills as $skill)
                                        <option value="{{$skill->name}}" {{(count($worker_skills_names>=2) && $worker_skills_names[1] == $skill->name) ? 'selected' : ''}}>{{$skill->name}}</option>
                                    @endforeach
                                </select>
                                <select class="form-control form-control-sm mb-3" id="skill-3" name="skill-3">
                                    <option selected value="">-</option>
                                    @foreach ($skills as $skill)
                                        <option value="{{$skill->name}}" {{(count($worker_skills_names>=3) && $worker_skills_names[2] == $skill->name) ? 'selected' : ''}}>{{$skill->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-4">
                            <div class="col-lg-12">
                                <button type="submit" class="badge btn btn-secondary float-right" style="font-size: initial;">Edit</button>
                            </div>
                        </div>
                    </form>
                @endif
            @endauth
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

    <h4 class="text-muted mt-4">campaigns</h4>
        
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

    <h4 class="text-muted">tasks</h4>

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
    
@endsection