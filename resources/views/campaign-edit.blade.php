@extends('app') 
@section('title', 'Edit campaign') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Create new task</div>

                <div class="card-body">
                    <form method="POST" id="create-task-form" action="{{ route('campaign.create.task.action') }}">
                        @csrf @method('POST')

                        <input type="hidden" name="campaign" value="{{ $campaign->id }}">

                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value=""
                                    required autofocus> @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control" name="description" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Available answers</label>

                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <input type="text" id="task-option-input" class="form-control{{ $errors->has('options') ? ' is-invalid' : '' }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" id="task-option-button">Add</button>
                                    </div>
                                    @if($errors->has('options'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('options') }}
                                    </div>
                                    @endif
                                </div>

                                <input type="hidden" name="options" value="[]">

                                <ul class="list-group" id="options-list">
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
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
                    <p class="text-muted text-center">No tasks yet
                        <p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection