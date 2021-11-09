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
<!-- Small boxes (Stat box) -->
    {{-- @if(isset($param['dont-show-summary']))
        <a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}" class="btn btn-success"><i class="fa fa-arrow-circle-left">&nbsp;</i>Back To CS Dashboard </a>
    @else
         
      <div class="row content-alert-notif-cs">
        <div class="col-lg-12">
          Customer Services Board 
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3> <span class="active-summary" style="font-size: 50px" > {{ $summary['active'] }} <span style="font-size: 10px"> Customer</span></span></h3>
              <p>Next Scheduled</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}?_token={{ csrf_token() }}&fbfl=status&q=ACTIVE&dont-show-summary=1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3> <span class="gate-in-summary" data-count="{{ $summary['gate_in'] }}" style="font-size: 50px" >  {{ $summary['gate_in'] }}<span style="font-size: 10px"> Customer</span></span></h3>
              <p>Today Gate In</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}?_token={{ csrf_token() }}&fbfl=status&q=GATE IN&dont-show-summary=1&search-by-date=on&dtr={{ urlencode(date('m/d/Y')) }}+-+{{ urlencode(date('m/d/Y')) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3> <span class="finished-summary" style="font-size: 50px" >  {{ $summary['finished'] }} <span style="font-size: 10px"> Customer</span></span></h3>
              <p>Today Finished</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}?_token={{ csrf_token() }}&fbfl=status&q=FINISHED&dont-show-summary=1&search-by-date=on&dtr={{ urlencode(date('m/d/Y')) }}+-+{{ urlencode(date('m/d/Y')) }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

         <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3> <span class="tomorrow-summary" style="font-size: 50px" >  {{ $summary['tomorrow'] }} <span style="font-size: 10px"> Customer</span></span></h3>
              <p>Tomorrow</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a href="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}?_token={{ csrf_token() }}&search-by-date=on&dtr={{ urlencode(date('m/d/Y', strtotime('+1 day', strtotime(date('Y-m-d')) ))) }}+-+{{ urlencode(date('m/d/Y', strtotime('+1 day', strtotime(date('Y-m-d')) ))) }}&fbfl=status&q=ACTIVE&dont-show-summary=1" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div> 
      </div>
    @endif --}}
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Lists') }} {{ $caption->name  }}</h3></div>
                @if (session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
                @endif
                <div class="box-body" >
                    <div class="row">
                          <form method="GET" action="{{ route(current(explode('.',Route::currentRouteName())) . '.index' ) }}">
                            @csrf
                                <div class="col-md-12">
                                        <div class="custom-search-form row">

                                            @if( isset($dateRangeField) ) 
                                             <div class="col-md-2">
                                                    <span> <label for="search-by-date">Search By Date</label> <input  @if( isset($param['search-by-date']) ) checked @endif  type="checkbox" name="search-by-date" id="search-by-date" class="minimal" /> </span>
                                                    <input type="text" class="form-control date-picker-range   " name="dtr"  @if( isset($param['dtr']) ) value="{{$param['dtr']}}" @endif >
                                            </div>
                                            @endif
                                            <div class="col-md-2 "> 
                                                    <span> Filter </span>
                                                    <select name="fbfl" class="select2 form-control ">
                                                        <option value="" >CHOOSE</option>
                                                        @foreach($columns as $k => $v)
                                                                @if( isset( $columns[$k]) && ( $columns[$k]->type == "hidden" || $columns[$k]->type == "hidden-list" ) ) 
                                                                @else 
                                                                    @if( isset( $columns[$k]) && isset($columns[$k]->filter) &&  $columns[$k]->filter == false ) 
                                                                    @else 
                                                                    <option value="{{ $k }}" @if(isset($param['fbfl']) ) @if( $k == $param['fbfl'] ) selected  @endif  @endif  >{{ strtoupper(implode(' ',explode('_', ( isset( $columns[$k]) && isset($columns[$k]->label)  ? $columns[$k]->label : $k )  ))) }}</option>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                    </select>
                                            </div>  
                                            <div class="col-md-3 "> 
                                                    <span> Search </span>
                                                    <input type="text" class="form-control " name="q" placeholder="Search..." value="{{$param['q']}}" > 
                                            </div>  
                                            <div class="col-md-4">
                                                <span> Action </span><br/>
                                                <button class="btn btn-default" type="submit">
                                                    <i class="fa fa-search"></i> {{ __('Search') }}
                                                </button>
                                                @if( $grantType->CREATE ) 
                                                <!-- <a class="btn btn-info  " href="{{ route(current(explode('.',Route::currentRouteName())) . '.create') }}">
                                                    <i class="fa fa-plus"></i> {{ __('Add') }}
                                                </a> -->
                                                @endif
                                                <a class="btn btn-success  " href="{{ route(current(explode('.',Route::currentRouteName())) . '.index') }}?export=1&q={{$param['q']}}{{ isset($param['search-by-date']) ? '&search-by-date=on' : '' }}&dtr={{$param['dtr']}}&fbfl={{ isset($param['fbfl']) ? $param['fbfl'] : '' }}">
                                                    <i class="fa fa-download"></i> {{ __('Download') }}
                                                </a>
                                            </div> 
                                        </div>
                                </div>
                                 
                         </form>
                    </div>
                    <br/>
                <div  style="overflow-x: auto;" >
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                @if( $grantType->UPDATE || $grantType->DELETE  )
                                <th>ACTIONS</th>
                                @endif
                                @forelse($items as  $item)
                                @if ($loop->first)
                                @foreach(json_decode($item,true) as $k => $v)
                                              @if( isset( $columns[$k]) && $columns[$k]->type == "hidden" || isset( $columns[$k]) && $columns[$k]->type == "textarea"  || ( isset( $columns[$k]) && $columns[$k]->type == "textarea-wysihtml5" ) || (isset( $columns[$k]) && $columns[$k]->type == "hidden-list")   ) 
                                              @else 
                                                 <th>{{ strtoupper(implode(' ',explode('_', ( isset( $columns[$k]) && isset($columns[$k]->label)  ? $columns[$k]->label : $k ) ))) }}</th>
                                                @endif

                                @endforeach
                                @break
                                @endif
                                @empty
                                <td>NO ITEM</td>
                                @endforelse
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $key => $item)
                            <tr>
                               <td>
                                    <div class="btn-group">
                                       @if( $grantType->UPDATE ) <button type="button" class="btn btn-info btn-sm" onclick="window.location.href='{{ route(current(explode('.',Route::currentRouteName())) . '.show', $item->id) }}'"> <i class="fa fa-search"></i></button> @endif 
                                    </div>
                                </td>
                                @forelse(json_decode($item,true) as $k => $v)
                                      
                                    @if( isset( $columns[$k]) && $columns[$k]->type == "file" || (isset( $columns[$k]) && $columns[$k]->type == "file-url") ) 
                                        @if( $columns[$k]->type == "file" ) 
                                            <td style="width:5%"><image style="width:100%" src="{{ asset($assetsPath.'/'.$v ) }}" />  </td>
                                        @endif
                                        @if( $columns[$k]->type == "file-url" ) 
                                            <td style="width:5%"><image style="width:100%" src="{{ $v }}" />  </td>
                                        @endif
                                    @else 
                                              @if( (isset( $columns[$k]) && $columns[$k]->type == "hidden") || (isset( $columns[$k]) && $columns[$k]->type == "textarea")  || (isset( $columns[$k]) && $columns[$k]->type == "textarea-wysihtml5")  || (isset( $columns[$k]) && $columns[$k]->type == "hidden-list") ) 
                                              @else 
                                                 @if( is_array( $v ) ) 
                                                        <td>
                                                            <table>
                                                    @forelse( $v as $kv => $vv)
                                                            <tr>
                                                                <td><strong>{{ $kv }}</strong> </td>
                                                                 @if( is_array( $vv ) ) 
                                                                    <td>{{ json_encode($vv) }}</td>
                                                                @else 
                                                                    <td>{{ $vv }}</td>
                                                                @endif
                                                            </tr>
                                                    @empty
                                                        <tr>
                                                            <td> no data </td>
                                                        </tr>
                                                    @endforelse
                                                            </table>
                                                        </td>
                                                 @else 
                                                        @if( isset( $columns[$k]) && $columns[$k]->type == "number" )  
                                                            <td>Rp. {{ number_format($v) }}</td>
                                                        @else 
                                                        <td>
                                                            @if( isset( $columns[$k] )  ) 
                                                                @forelse( $columns[$k]->data as  $collection)
                                                                    @if( isset($collection->id ) &&  $v==$collection->id ) 
                                                                        {{ $collection->name }}
                                                                    @endif 
                                                                @empty
                                                                    @if( isset( $columns[$k]) && $columns[$k]->type == "text-long" )  
                                                                        <div style="width:150px;overflow: auto;height: 60px;">{{ $v }}</div>
                                                                    @else
                                                                        {{ $v }}
                                                                    @endif
                                                                @endforelse
                                                            @else
                                                                {{ $v }}
                                                            @endif
                                                            </td>
                                                        @endif
                                                @endif
                                            @endif
                                                                                
                                    @endif

                                @empty
                                <td>&nbsp;</td>
                                @endforelse
                               
                            </tr>
                            @empty
                            <tr>
                                <td>No entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                    {{ $items->appends($param)->links() }}
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
setInterval(getSummmaryData, 3000);
});

function getSummmaryData(){
  let base_url = "{{env('APP_URL')}}";
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
    method: "GET",
    url: base_url + "/admin/appointment-manage/appointment-summary-data",
    data: { _token: CSRF_TOKEN, latest_gate_in : $(".gate-in-summary").data("count") },
    dataType: 'JSON',
    beforeSend: function(){

    },
    error: function(event, response) {
      return false;
    },
      success: function(event, response) {

        if(event.has_new_gate_in) {
             var html = `
                <div class="alert alert-info alert-dismissible" style="position: fixed;z-index: 1;right: 0;bottom: 0;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> Alert!</h4>
                    You Have New Customer At Gate
                </div>
            `;
            $(".content-alert-notif-cs").append(html);
        }

        $(".active-summary").html(event.active);
        $(".gate-in-summary").html(event.gate_in);
        $(".gate-in-summary").data("count",event.gate_in);
        $(".finished-summary").html(event.finished);
        $(".tomorrow-summary").html(event.tomorrow);
        console.log(event);
      return false;
    }
  });
}
</script>
@endsection


