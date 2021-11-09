@extends('layouts.app')

@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">{{ $caption->menu->name }}</li>
        <li class=""><a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index') }}">{{ $caption->name }}</a></li>
        <li class="active">Edit {{ $caption->name }}</li>
      </ol>
</section>
<div class="content">
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Edit Item ') }} {{ $caption->name }} </h3></div>
                    @if ($errors->count() > 0)
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                <div class="box-body">
                    <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.update', $model->id ) }}">
                  @method('PUT')
                  @csrf
                           @forelse(  json_decode($model)  as  $key => $val )
                                        @if ( $key != 'id'  ) 
                                            <div class="form-group row @if( isset($columns[$key]) && $columns[$key]->type == "hidden" ) {{ 'hide' }} @endif ">
                                                @if( isset($columns[$key]) && $columns[$key]->type == "hidden" )
                                                @else
                                                <label for="name" class="col-md-2 col-form-label text-md-right">{{ __(   strtoupper(implode(' ',explode('_',( isset( $columns[$key]) && isset($columns[$key]->label)  ? $columns[$key]->label : $key )))) ) }}</label>
                                                @endif
                                              
                                                    <div class="col-md-10">
                                                        @if( isset($columns[$key]) && $columns[$key]->data  ) 
                                                                <select class="select2  @if($columns[$key]->type=='multiple-select') {{ 'col-lg-6' }} @endif " 
                                                                    @if($columns[$key]->type=='multiple-select') 
                                                                        name="{{ $key }}[]" 
                                                                    @else 
                                                                        name="{{ $key }}" 
                                                                    @endif 
                                                                    @if($columns[$key]->type=='multiple-select') {{ 'multiple="multiple" ' }} @endif
                                                                    @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif 
                                                                    @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    @forelse( $columns[$key]->data as  $collection)
                                                                        <option value="{{ $collection->id }}" 
                                                                                @if($columns[$key]->type=='multiple-select') 
                                                                                    @if(is_array( json_decode($val)))
                                                                                        @forelse( json_decode($val) as $vval )
                                                                                            @if($vval==$collection->id) {{ 'selected' }} @endif 
                                                                                        @empty
                                                                                        @endforelse
                                                                                    @else
                                                                                    @endif 
                                                                                @else
                                                                                    @if($val==$collection->id) {{ 'selected' }} @endif 
                                                                                @endif 

                                                                        >
                                                                                {{ $collection->name }}
                                                                        </option>
                                                                    @empty
                                                                        <option>NO ITEM</option>
                                                                    @endforelse
                                                                </select>
                                                        @else
                                                            @if( in_array($columns[$key]->type,["text","checkbox","radio","file","hidden","number"] ) )
                                                                <input  id="{{ $key }}" type="{{ $columns[$key]->type }}" class="@if(  in_array($columns[$key]->type, ['text','number'] ) ) {{ 'form-control' }} @endif   "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                                @if( $columns[$key]->type == "file" ) 
                                                                    <image src="{{ asset($assetsPath.'/'.$val ) }}" />   
                                                                @endif
                                                            @endif
                                                            @if( in_array($columns[$key]->type,["text-datepicker"] ) )
                                                                <input  id="{{ $key }}" type="{{ $columns[$key]->type }}" data-date-format="yyyy-mm-dd"  class="date-picker @if(  in_array($columns[$key]->type, ['text-datepicker' ] ) ) {{ 'form-control' }} @endif   "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if( in_array($columns[$key]->type,["text-timepicker"] ) )
                                                                <input  id="{{ $key }}" type="{{ $columns[$key]->type }}"   class="time-picker form-control      "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if($columns[$key]->type == "textarea" )
                                                                <textarea  id="{{ $key }}" type="{{ $columns[$key]->type }}" class="form-control   " name="{{ $key }}"   @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    {{ $val }}
                                                                </textarea> 
                                                            @endif
                                                              @if($columns[$key]->type == "textarea-wysihtml5" )
                                                                <textarea  id="{{ $key }}" type="{{ $columns[$key]->type }}" class="form-control wysihtml5-editor    " name="{{ $key }}"   @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    {{ $val }}
                                                                </textarea> 
                                                            @endif
                                                            @if( $columns[$key]->type == "file-url" )
                                                                <image src="{{ $val }}" />   
                                                            @endif
                                                            @if( $columns[$key]->type == "label" )
                                                                {{ $val }}
                                                            @endif
                                                            @if( $columns[$key]->type == "text-long" )
                                                                <input  id="{{ $key }}" type="text" class="{{ 'form-control' }}"  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if( $columns[$key]->type == "hidden-list" )
                                                                <input  id="{{ $key }}" type="text" class="{{ 'form-control' }}"  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                        @endif
                                                      
                                                    </div>
                                            </div>
                                        @endif
  
                                @empty
                            <td>NO ITEM</td>
                        @endforelse

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
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
