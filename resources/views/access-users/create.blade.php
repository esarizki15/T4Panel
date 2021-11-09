@extends('layouts.app')
@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">Users</li>
        <li class=""><a href="{{ route('access-users.index') }}">API Users</a></li>
        <li class="active">Create User Access</li>
    </ol>
</section>
<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-10" style="margin-top: 50px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Create User Access') }}</h3></div>
                @if ($errors->count() > 0)
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                <div class="box-body">
                    <form method="POST" action="{{ route('access-users.store') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="username" class="col-md-3 col-form-label text-md-right">{{ __('Username') }}</label>
                                    <div class="col-md-9">
                                        <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required>
                                        @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>
                                    <div class="col-md-9">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-3 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                    <div class="col-md-9">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label for="fullname" class="col-md-3 col-form-label text-md-right">{{ __('Full Name') }}</label>
                                    <div class="col-md-9">
                                        <input id="fullname" type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname" value="{{ old('fullname') }}" required autofocus>
                                        @if ($errors->has('fullname'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('fullname') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-md-3 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                    <div class="col-md-9">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label for="role_code"
                                    class="col-md-2 col-form-label text-md-left">{{ __('Role') }}</label>
                                    <div class="col-md-5">
                                        <select class="select2 form-control" name="role_code">
                                            <option value="VIEWER" >CHOOSE</option>
                                            <option value="VIEWER" >VIEWER</option>
                                            <option value="MODERATOR" >MODERATOR</option>
                                            <option value="ADMIN" >ADMIN</option>
                                            <option value="SUPERUSER" >SUPER USER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="active"
                                    class="col-md-2 col-form-label text-md-left">{{ __('Active') }}</label>
                                    <div class="col-md-5">
                                        <select class="select2 form-control" name="active">
                                            <option value="1" >YES</option>
                                            <option value="0" >NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
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