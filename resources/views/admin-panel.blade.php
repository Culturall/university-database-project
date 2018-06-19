@extends('app') 
@section('title', 'Edit campaign') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Promote workers to requesters</div>
            </div>
        </div>

        @if ($errors->has('exception'))
            <div class="col-lg-8">
            <div class="alert alert-danger mt-4" role="alert">
                {{ $errors->first('exception') }}
            </div>
        </div>
        @endif

        <div class="col-md-8">
            <h4 class="text-muted mt-4">Admin</h4>
            <div class="col-xs-12 mt-4 col-lg-6">
                <ul class="list-group">
                    @forelse ($admins as $admin)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $admin->name . ' ' . $admin->surname . ' (' . $admin->email . ')' }}
                            @if (!$admin->requester)
                                <a href="{{ route('admin.promote') . '?worker=' . $admin->id }}" class="badge badge-success badge-pill" style="color: white;">Promote</a>
                            @endif    
                        </li>
                    @empty
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                            No admins
                            </li>
                    @endforelse
                </ul>
            </div>
            
            <h4 class="text-muted mt-4">Pendings</h4>
            <div class="col-xs-12 mt-4 col-lg-6">
                <ul class="list-group">
                    @forelse ($workers as $worker)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $worker->name . ' ' . $worker->surname . ' (' . $worker->email . ')' }}
                            <a href="{{ route('admin.promote') . '?worker=' . $worker->id }}" class="badge badge-success badge-pill" style="color: white;">Promote</a>
                            </li>
                    @empty
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                            No pending requesters
                            </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection