@extends('layouts.app')
@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">{{ $caption->menu->name }}</li>
        <li class="active">{{ $caption->name  }}</li>
      </ol>
</section>
<div class="content">
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border text-center"><h3 class="box-title"> {{ $caption->name  }}</h3></div>
               
                <div class="box-body" >
                    <div class="row text-center" >
                        <div class="col-lg-12 ">
                            <div class="row form-group ">
                                <div class="col-lg-12"> 
                                    <label>{{ $items->setting_desc }} : </label> 
                                    <h3>{{ substr($items->setting_input,6,2) }}/{{ substr($items->setting_input,4,2) }}/{{ substr($items->setting_input,0,4) }}</h3>
                               </div>
                            </div> 
                            <div class="row form-group ">
                                <div class="col-lg-12">
                                 <label> OTP For Acquisition :   </label>
                                    <h3>{{ $items->setting_val_str }}</h3>
                               </div>
                            </div> 
                            <div class="row form-group ">
                                <div class="col-lg-12">
                                     <button type="button" class="btn btn-success " onclick="window.location.href='{{ route(current(explode('.',Route::currentRouteName())). '.generate' ) }}'"> <i class="fa fa-refresh"></i> Generate Today OTP</button> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 @if (session('message'))
                <div class="alert alert-info  text-center">{{ session('message') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection