@extends('layouts.app')

@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">{{ $caption->menu->name }}</li>
        <li class=""><a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index') }}">{{ $caption->name }}</a></li>
        <li class="active">Show {{ $caption->name }}</li>
      </ol>
</section>
<div class="content">
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __('Show Item ') }} {{ $caption->name }} </h3>
                    <span class="pull-right" style="width: 50%;"> 
                    {{-- @if( json_decode($model)->status  == 1 || json_decode($model)->status  == 6  )  --}}
                    @if($model->status  == 1 || $model->status  == 6  ) 
                    <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.update-start', $model->id ) }}">
                        @method('PUT')
                        @csrf
                        <button type="submit" class="btn btn-primary pull-right" style="margin-left: 10px;">
                                    {{ __('Start Handling') }}
                        </button>
                    </form>
                    @endif
                    {{-- @if( json_decode($model)->status  == 8 )  --}}
                    @if( $model->status  == 8 )
                        <div class="modal fade" id="modal-default">
                          <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.update-stop', $model->id ) }}">
                                    @method('PUT')
                                    @csrf
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Stop Handling Customer</h4>
                                      </div>
                                      <div class="modal-body">
                                        <div  > <label>Notes</label> <textarea  name="notes" class="form-control" ></textarea></div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                      </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                       <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-default">
                        {{ __('Stop Handling') }}
                      </button>
                        
                    @endif
                    {{-- @if( json_decode($model)->status  == 1 )  --}}
                    @if( $model->status  == 1 ) 
                     <div class="modal fade" id="modal-cancel">
                          <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.update-cancel', $model->id ) }}">
                                    @method('PUT')
                                    @csrf
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Cancel Booking Customer</h4>
                                      </div>
                                      <div class="modal-body">
                                        <div  > <label>Notes</label> <textarea  name="notes" class="form-control" ></textarea></div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                      </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                          </div>
                          <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
                       <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-cancel">
                        {{ __('Cancel Booking') }}
                      </button>
                    @endif

                    @if ($model->status == 2)
                        <div class="modal fade" tabindex="-1" role="dialog" id="modal-update">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="POST" enctype="multipart/form-data" action="{{ route(current(explode('.',Route::currentRouteName())) . '.update-notes', $model->id ) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Update Booking Notes</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div  > <label>Notes</label> <textarea  name="notes" class="form-control" >{{$model->notes}}</textarea></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-update">{{ __('Update Notes') }}</button>
                    @endif

                    </span>
                </div>
                 @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
                @endif
                    @if ($errors->count() > 0)
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                <div class="box-body">
                    @php
                        // dd($model);
                    @endphp
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">CODE</label></div>
                                <div class="col-md-8">{{$model->appointment_code}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">SERVICE</label></div>
                                <div class="col-md-8">{{$model->service_name}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">SUB SERVICE</label></div>
                                <div class="col-md-8">{{$model->sub_service_name}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">LOCATION</label></div>
                                <div class="col-md-8">{{$model->location_name}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">GATE IN BY</label></div>
                                <div class="col-md-8">{{$model->gate_in_received_by}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">CS OFFICER</label></div>
                                <div class="col-md-8">{{ucwords($model->handling_by_panel)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">STATUS</label></div>
                                @php
                                    $start_date = new DateTime($model->handling_start);
                                    $since_start = $start_date->diff(new DateTime($model->handling_end));
                                    $total_time = "{$since_start->h} hours, {$since_start->i} minutes, {$since_start->s} seconds";
                                    // echo $since_start->days.' days total<br>';
                                    // echo $since_start->y.' years<br>';
                                    // echo $since_start->m.' months<br>';
                                    // echo $since_start->d.' days<br>';
                                    // echo $since_start->h.' hours<br>';
                                    // echo $since_start->i.' minutes<br>';
                                    // echo $since_start->s.' seconds<br>';
                                @endphp 
                                <div class="col-md-8">{{$model->status_name}} {{in_array($model->status, [2, 8, 9]) ? '(' . $total_time . ')' : ''}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">CUSTOMER</label></div>
                                <div class="col-md-8">{{ucwords($model->member_name)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">UNIT ADDRESS</label></div>
                                <div class="col-md-8">{{$model->member_address}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">PLAT NUMBER</label></div>
                                <div class="col-md-8">{{$model->identity_code}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">DESCRIPTION</label></div>
                                <div class="col-md-8">{{$model->appointment_description}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">BOOKING DATE</label></div>
                                <div class="col-md-8">{{$model->picked_date}}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label class="text-md-right">BOOKING TIME</label></div>
                                <div class="col-md-8">{{$model->picked_time}}</div>
                            </div>
                            @if( $model->status  == 3 )
                                <div class="row">
                                    <div class="col-md-4"><label class="text-md-right">RESCHEDULE BOOKING DATE</label></div>
                                    <div class="col-md-8">{{$model->reschedule_date_to}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><label class="text-md-right">RESCHEDULE BOOKING TIME</label></div>
                                    <div class="col-md-8">{{$model->reschedule_time_to}}</div>
                                </div>
                            @endif
                        </div>
                        @if ($model->status == 2)
                            <div class="col-md-12">
                                <br>
                                <label class="text-md-right">NOTES</label>
                                <textarea class="form-control" rows="3" placeholder="Enter ..." readonly>{{$model->notes}}</textarea>
                            </div>
                        @endif
                    </div> <br>
                           {{-- @forelse(  json_decode($model)  as  $key => $val )
                                        @if ( $key != 'id'  ) 
                                            <div class="form-group row @if( isset($columns[$key]) && $columns[$key]->type == "hidden" ) {{ 'hide' }} @endif ">
                                                @if( isset($columns[$key]) && $columns[$key]->type == "hidden" )
                                                @else
                                                <label for="name" class="col-md-2 col-form-label text-md-right">{{ __(   strtoupper(implode(' ',explode('_',( isset( $columns[$key]) && isset($columns[$key]->label)  ? $columns[$key]->label : $key )))) ) }}</label>
                                                @endif
                                              
                                                    <div class="col-md-10">
                                                        @if( isset($columns[$key]) && $columns[$key]->data  ) 
                                                                <select disabled class="select2" style="width:300px" name="{{ $key }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif  @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    @forelse( $columns[$key]->data as  $collection)
                                                                        <option value="{{ $collection->id }}" @if($val==$collection->id) {{ 'selected' }} @endif >{{ $collection->name }}</option>
                                                                        @empty
                                                                        <option>NO ITEM</option>
                                                                    @endforelse
                                                                </select>
                                                        @else
                                                            @if( in_array($columns[$key]->type,["text","checkbox","radio","file","hidden","number"] ) )
                                                                <input  disabled id="{{ $key }}" type="{{ $columns[$key]->type }}" class="@if(  in_array($columns[$key]->type, ['text','number'] ) ) {{ 'form-control' }} @endif   "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                                @if( $columns[$key]->type == "file" ) 
                                                                    <image src="{{ asset($assetsPath.'/'.$val ) }}" />   
                                                                @endif
                                                            @endif
                                                            @if( in_array($columns[$key]->type,["text-datepicker"] ) )
                                                                <input  disabled  id="{{ $key }}" type="{{ $columns[$key]->type }}" data-date-format="yyyy-mm-dd"  class="date-picker @if(  in_array($columns[$key]->type, ['text-datepicker' ] ) ) {{ 'form-control' }} @endif   "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if( in_array($columns[$key]->type,["text-timepicker"] ) )
                                                                <input disabled   id="{{ $key }}" type="{{ $columns[$key]->type }}"   class="time-picker form-control      "  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if($columns[$key]->type == "textarea" )
                                                                <textarea  disabled id="{{ $key }}" type="{{ $columns[$key]->type }}" class="form-control   " name="{{ $key }}"   @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
                                                                    {{ $val }}
                                                                </textarea> 
                                                            @endif
                                                              @if($columns[$key]->type == "textarea-wysihtml5" )
                                                                <textarea  disabled  id="{{ $key }}" type="{{ $columns[$key]->type }}" class="form-control wysihtml5-editor    " name="{{ $key }}"   @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  >
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
                                                                <input disabled   id="{{ $key }}" type="text" class="{{ 'form-control' }}"  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                            @if( $columns[$key]->type == "hidden-list" )
                                                                <input  disabled  id="{{ $key }}" type="text" class="{{ 'form-control' }}"  name="{{ $key }}" value="{{ $val }}" @if($columns[$key]->not_required=='NOT') {{ 'required' }} @endif @if($columns[$key]->disabled=='disabled') {{ 'disabled' }} @endif  /> 
                                                            @endif
                                                        @endif
                                                      
                                                    </div>
                                            </div>
                                        @endif
  
                                @empty
                            <td>NO ITEM</td>
                        @endforelse --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a href="{{  route(current(explode('.',Route::currentRouteName())) . '.index') }}" class="btn btn-warning">
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
