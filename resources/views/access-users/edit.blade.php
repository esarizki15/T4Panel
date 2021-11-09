@extends('layouts.app')
@section('content')
<section class="content-header">
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="">Users</li>
    <li class=""><a href="{{ route('access-users.index') }}">API Users</a></li>
    <li class="active">Edit User Access</li>
  </ol>
</section>
<div class="content">
  <div class="row justify-content-center">
    <div class="col-md-12" style="margin-top: 50px;">
      <div class="box">
        <div class="box-header with-border"><h3 class="box-title">{{ __('Update Users') }}</h3></div>
        @if (session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
        @endif
        <div class="box-body">
          <form method="POST" action="{{ route('access-users.update', $users->id ) }}">
            @method('PUT')
            @csrf
            <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="username" class="col-md-3 col-form-label text-md-right">{{ __('Username') }}</label>
                            <div class="col-md-9">
                                <input id="username" type="username" readonly class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $users->username }}"  required>
                                @if ($errors->has('username'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Change Password') }}</label>
                            <div class="col-md-9">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  >
                                @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-3 col-form-label text-md-right">{{ __('Confirm Change Password') }}</label>
                            <div class="col-md-9">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  >
                            </div>
                        </div>

                        <hr>
                        <div class="form-group row">
                            <label for="fullname" class="col-md-3 col-form-label text-md-right">{{ __('Full Name') }}</label>
                            <div class="col-md-9">
                                <input id="fullname" type="text" class="form-control{{ $errors->has('fullname') ? ' is-invalid' : '' }}" name="fullname"  value="{{ $users->fullname }}"  required autofocus>
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
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $users->email }}"  required>
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
                                    <option value="VIEWER" @if( $users->role_code == 'VIEWER' ) selected @endif >VIEWER</option>
                                    <option value="MODERATOR" @if( $users->role_code == 'MODERATOR' ) selected @endif  >MODERATOR </option>
                                    <option value="ADMIN" @if( $users->role_code == 'ADMIN' ) selected @endif  >ADMIN </option>
                                    <option value="SUPERUSER" @if( $users->role_code == 'SUPERUSER' ) selected @endif  >SUPER USER</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="active"
                            class="col-md-2 col-form-label text-md-left">{{ __('Active') }}</label>
                            <div class="col-md-5">
                                <select class="select2 form-control" name="active">
                                    <option value="1" @if( $users->active == 1 ) selected @endif >YES</option>
                                    <option value="0"  @if( $users->active == 0 ) selected @endif  >NO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            
            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                {{ __('Update') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-md-12" style="margin-top: 50px;">
      <div class="box">
        <div class="box-header with-border"><h3 class="box-title">{{ __('Access Menu') }}</h3></div>
        <div class="box-body">
          <table class="table table-bordered table-striped">
            <tr> <th>  Menu</th><th>?Grant</th></tr>
            @forelse($menus as  $menu)
            <tr>    <th> {{ $menu->name }} </th>
            <th>
              <label for="check-all-menu-{{ $menu->id }} "  class="  col-form-label text-md-left" >
                <input id="check-all-menu-{{ $menu->id }}" type="checkbox" class="minimal-red all-user-menu "  data-menu="{{ $menu->id }}" @if(array_key_exists( $menu->id, json_decode($users->accessmenu->groupBy('menu_id'),true)  ) ) {{ 'checked' }} @endif />
              All</label>
            </th>
          </tr>
          @forelse($menu->submenu as $submenu)
          <tr >
            <td> &nbsp; &nbsp; {{ $submenu->name }}</td>
            <td> <input type="checkbox" class="minimal add-user-menu all-check-menu-{{ $menu->id }} "  data-user="{{ $users->id }}" data-menu="{{ $menu->id }}" data-submenu="{{ $submenu->id  }}" @if(array_key_exists( $submenu->id, json_decode($users->accessmenu->groupBy('sub_menu_id'),true) ) ) {{ 'checked' }} @endif /> </td>
          </tr>
          @empty
          @endforelse
          @empty
          @endforelse
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@section('javascript')
<script>

$(document).ready(function(){
let base_url = "{{env('APP_URL')}}";
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$(document).on('ifChanged','.all-user-menu', function(event){
var thisobj  = $(this);
if(event.target.checked){
$(".all-check-menu-"+thisobj.data("menu")).iCheck('check');
}else{
$(".all-check-menu-"+thisobj.data("menu")).iCheck('uncheck');
}
});

$(document).on('ifChanged','.add-user-menu', function(event){

var thisobj  = $(this);
if(event.target.checked){
$.ajax({
method: "POST",
url: base_url + "/api/users/menu/add",
data: { user_id : thisobj.data("user") , menu_id: thisobj.data("menu") ,  sub_menu_id: thisobj.data("submenu") ,  _token: CSRF_TOKEN },
dataType: 'JSON',
beforeSend: function(){

},
error: function(event, response) {
alert( "Data fail: " + event );

return false;
},
success: function(event, response) {
}
});
}else{
$.ajax({
method: "POST",
url: base_url +  "/api/users/menu/remove",
data: {    user_id : thisobj.data("user") , menu_id: thisobj.data("menu") ,  sub_menu_id: thisobj.data("submenu") , _token: CSRF_TOKEN },
dataType: 'JSON',
beforeSend: function(){

},
error: function(event, response) {
alert( "Data fail: " + event );

return false;
},
success: function(event, response) {
}
});
}
});
});

</script>
@endsection