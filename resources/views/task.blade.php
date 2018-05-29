@extends('app') 
@section('title', $task ? $task->title : 'Not allowed') 
@section('content')
@if ($task)
    <?php $campaign = $task->partOf; ?>
    <div class="row">
        <div class="col-lg-9">
            <h1 class="display-1">{{$task->title}}</h1>
        <h1 class="display-4 text-muted"><i>part of</i> <a href="{{route('campaign', $campaign->id)}}">{{$campaign->title}}</a></h5>
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
@else 
    <div class="text-center">Not allowed</div>
@endif
@endsection