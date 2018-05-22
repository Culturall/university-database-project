@extends('layouts.app') @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Register</div>

                <div class="card-body">
                    <form method="POST"
                          action="{{ route('register') }}">
                        @csrf @method('POST')

                        <div class="form-group row">
                            <label for="name"
                                   class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name"
                                       type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       autofocus> @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname"
                                   class="col-md-4 col-form-label text-md-right">Surname</label>

                            <div class="col-md-6">
                                <input id="surname"
                                       type="text"
                                       class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}"
                                       name="surname"
                                       value="{{ old('surname') }}"
                                       required
                                       autofocus> @if ($errors->has('surname'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('surname') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="birthdate"
                                   class="col-md-4 col-form-label text-md-right">Birthdate</label>

                            <div class="col-md-6">
                                <div class="input-group date"
                                     data-provide="datepicker"
                                     data-date-format="yyyy/mm/dd"
                                     data-date-end-date="0d">
                                    <input type="text"
                                           class="form-control"
                                           name="birthdate">
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
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email"
                                       type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required> @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password"
                                       type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password"
                                       required> @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm"
                                   class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm"
                                       type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="skills"
                                   class="col-md-4 col-form-label text-md-right">Skills</label>
                            <div class="col-md-6">
                                <select name="skills[]"
                                        class="custom-select"
                                        multiple
                                        size="3">
                                    <option selected
                                            value="">-</option>
                                    @foreach ($skills as $skill)
                                    <option value="{{$skill->name}}">{{$skill->name}}</option>
                                    @endforeach
                                </select>
                                <small id="skillsHelp"
                                       class="form-text text-muted">You can choose up to 3 skills to start working. Pay attention, you can't change them later!
                                    <br>They'll have 2.5 points initial value</small>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit"
                                        class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection