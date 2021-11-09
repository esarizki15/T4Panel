@extends('layouts.app')

@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">{{ $caption->menu->name }}</li>
        <li class=""><a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index') }}"> {{ $caption->name }} </a></li>
        <li class="active">Create  {{ $caption->name }} </li>
      </ol>
</section>
<div class="content">
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Create Batch New Item') }} {{ $caption->name }} </h3></div>
                    @if ($errors->count() > 0)
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.store-batch') }}">
                        {{ csrf_field() }}
                            <div class="form-group row ">
                                <label for="title" class="col-md-2 col-form-label text-md-right">TITLE</label>
                                <div class="col-md-10">
                                    <div class="col-md-4 col-lg-6 ">
                                        <input  id="title" type="text" class="{{ 'form-control' }}" name="title" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="content" class="col-md-2 col-form-label text-md-right">CONTENT</label>
                                <div class="col-md-10">
                                    <div class="col-md-11">
                                        <input  id="content" type="text" class="{{ 'form-control' }}" name="content" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="image_url" class="col-md-2 col-form-label text-md-right">IMAGE URL</label>
                                <div class="col-md-10">
                                    <div class="col-md-11">
                                        <input  id="image_url" type="text" class="{{ 'form-control' }}" name="image_url" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="url" class="col-md-2 col-form-label text-md-right">URL TO OPEN</label>
                                <div class="col-md-10">
                                    <div class="col-md-11">
                                        <input  id="url" type="text" class="{{ 'form-control' }}" name="url" value="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row ">
                                <label for="name" class="col-md-2 col-form-label text-md-right">SEND TO</label>
                                <div class="col-md-10">
                                    <div class="col-md-12">
                                        @forelse($columns as  $column)
                                            @if( $column->name == 'member_id')
                                                 @if( $column->data  ) 
                                                    <select  style="width:80%" multiple="multiple" class="select2" name="{{ $column->name }}[]" >
                                                            <option value="ALL_USERS">ALL USERS</option>
                                                            <option value="ALL_RESIDENT">ALL RESIDENT</option>
                                                            <option value="ALL_NON_RESIDENT">ALL NON RESIDENT</option>
                                                            <option value="ALL_MERCHANT">ALL MERCHANT</option>
                                                        @forelse($column->data as  $collection)
                                                                @if( $collection->id == null )
                                                                @else
                                                                <option value="{{ $collection->id }}" >{{ $collection->name }}</option>
                                                                @endif
                                                            @empty
                                                            <option>NO ITEM</option>
                                                        @endforelse
                                                    </select>
                                                    @endif 
                                             @endif 
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                                   
                                      
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                                 <a href="{{  route(current(explode('.',Route::currentRouteName())) . '.index') }}" class="btn btn-warning">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script>
    $( document ).ready(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        
    });
</script>
@endsection

