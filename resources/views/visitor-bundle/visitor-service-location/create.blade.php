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
                <div class="box-header with-border"><h3 class="box-title">{{ __('Create New Item') }} {{ $caption->name }} </h3></div>
                    @if ($errors->count() > 0)
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.store') }}">
                        {{ csrf_field() }}
                                @forelse($columns as  $column)
                                    @if ($column->name != 'id' ) 

                                    <div class="form-group row @if( $column->type == "hidden" ) {{ 'hide' }}  @endif  ">
                                          @if( $column->type == "hidden" )
                                                @else
                                        <label for="name" class="col-md-2 col-form-label text-md-right">{{ __(   strtoupper(implode(' ',explode('_',  $column->label ))) ) }}</label>
                                                    @endif
                                      
                                        <div class="col-md-10">
                                            <div class="col-md-11">
                                                @if( $column->data  ) 
                                                        <select class="select2 @if($column->type=='multiple-select') {{ 'col-lg-6' }} @else {{ 'col-lg-4' }}  @endif " 
                                                                @if($column->type=='multiple-select') {{ 'multiple="multiple" ' }} @endif 
                                                                @if($column->not_required=='NOT') {{ 'required' }} @endif  
                                                                @if($column->type=='multiple-select') 
                                                                    name="{{ $column->name }}[]"  
                                                                @else
                                                                    name="{{ $column->name }}"  
                                                                @endif
                                                                @if($column->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    @forelse($column->data as  $collection)
                                                                        <option value="{{ $collection->id }}" >{{ $collection->name }}</option>
                                                                        @empty
                                                                        <option>NO ITEM</option>
                                                                    @endforelse
                                                        </select>
                                                @else
                                                    @if( in_array($column->type,["text","checkbox","radio","file","hidden", "number"] ) )
                                                        <input  id="{{ $column->name }}" type="{{ $column->type }}" class="@if(  in_array($column->type, ['text','number'] ) ) {{ 'form-control' }} @endif   " name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif 
                                                    @if( in_array($column->type,["text-datepicker"] ) )
                                                        <input  id="{{ $column->name }}" type="text" class=" date-picker form-control  " data-date-format="yyyy-mm-dd"    name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif
                                                    @if( in_array($column->type,["text-timepicker"] ) )
                                                        <input  id="{{ $column->name }}" type="text" class="time-picker form-control  "    name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif
                                                    @if($column->type == "textarea" )
                                                        <textarea  id="{{ $column->name }}" type="{{ $column->type }}" class="form-control   " name="{{ $column->name }}"   @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                            {{ old($column->name) }}
                                                        </textarea> 
                                                    @endif
                                                    @if($column->type == "textarea-wysihtml5" )
                                                        <textarea  id="{{ $column->name }}" type="{{ $column->type }}" class="form-control wysihtml5-editor  " name="{{ $column->name }}"   @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                            {{ old($column->name) }}
                                                        </textarea> 
                                                    @endif
                                                    @if( $column->type == "file-url" )
                                                        <input  id="{{ $column->name }}" type="text" class="{{ 'form-control' }}" name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif 
                                                    @if( in_array($column->type,["text-long"] ) )
                                                        <input  id="{{ $column->name }}" type="text" class="form-control" name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif
                                                    @if( in_array($column->type,["hidden-list"] ) )
                                                        <input  id="{{ $column->name }}" type="text" class="form-control" name="{{ $column->name }}" value="{{ old($column->name) }}" @if($column->not_required=='NOT') {{ 'required' }} @endif @if($column->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-1">
                                                     <span  >@if($column->not_required=='NOT') {{ '*' }} @endif  </span>
                                            </div>
                                        </div>
                                    </div>
                                            @endif
  
                                @empty
                            <td>NO ITEM</td>
                        @endforelse
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
