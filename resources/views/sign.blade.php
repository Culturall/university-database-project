@extends('app')

@section('title', 'Sign')

@section('content')
        <h4>Login</h4>
        <form>
            @csrf
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <h4>Register</h4>
        <form>
            @csrf
            <div class="form-group">
                <label for="name">Email address</label>
                <input type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="name">Skills</label>
                <select class="form-control form-control" multiple aria-describedby="selectHelp">
                    @foreach ($skills as $skill)
                        <option value="{{$skill->name}}">{{$skill->name}}</option>
                    @endforeach
                </select>
                <small id="selectHelp" class="form-text text-muted">Select multiple skills, up to 3</small>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
@endsection