@extends('layouts.app')

@section('login-form')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Cerberus') }}</title>
        
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <!-- Styles -->
        <link href="{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/layout/admin.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/layout/_all-skins.min.css') }}">
        <link href="{{ asset('css/select2/core.css') }}" rel="stylesheet">
        
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    </head>
<body class="skin-red hold-transition login-page">

<div class="login-box">
  <div class="login-logo"><b>{{ config('app.name', 'Cerberus') }}</b></div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-sm-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Login') }}
                                </button>

                                
                            </div>
                        </div>
                    </form>
                   
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->


</body>
<!-- Scripts -->
        <script src="{{ asset('js/layout/app.js') }}"  ></script>
        <script src="{{ asset('js/layout/admin.min.js') }}"  ></script>
        <script src="{{ asset('js/jquery/dist/jquery.min.js') }}"  ></script>
        <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"  ></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{ asset('css/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        @yield('javascript')
    </body>
</html>




@endsection