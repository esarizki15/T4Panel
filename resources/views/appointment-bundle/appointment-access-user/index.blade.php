@extends('layouts.app')
@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">Users</li>
        <li class="active">User Access</li>
      </ol>
</section>
<div class="content">
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">API Users</h3></div>
                @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
                @endif   
                <div class="box-body">
                    <div class="row">
                        <form method="GET" action="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}">
                            @csrf
                            <div class="col-md-12">
                                <div class="custom-search-form row">
                                    <div class="col-md-8 "> 
                                        <div class="input-group custom-search-form">
                                            <input type="text" class="form-control" name="q" placeholder="Search..." value="{{$param['q']}}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default-sm" type="submit">
                                                <i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 ">
                                        <a class="nav-link btn btn-app" href="{{ route('appointment-access-user.create') }}"><i class="fa fa-plus"></i> {{ __('Register') }}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <br />
                    <div style="overflow-x: auto;" >
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Fullname</th>
                                    <th>Username </th>
                                    <th>Email </th>
                                    <th>Role </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role_code }}</td>
                                    <td>
                                        
                                        <form action="{{ route('appointment-access-user.destroy', $user->id) }}" method="POST"
                                              style="display: inline"
                                              onsubmit="return confirm('Are you sure?');">
                                              <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='{{ route('appointment-access-user.edit', $user->id) }}'"> <i class="fa fa-edit"></i></button>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    {{ csrf_field() }}
                                                    <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                                                </div>

                                            
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No entries found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table> 
                    </div>   
                    {{ $users->links() }} 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection	
