@extends('layouts.app')

@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="">{{ $caption->menu->name }}</li>
        <li class="active">{{ $caption->name  }}</li>
      </ol>
</section>

<div class="content" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Lists') }} {{ $caption->name  }}</h3></div>
                @if (session('message'))
                    <div class="alert alert-info">{{ session('message') }}</div>
                @endif
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th colspan=2>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($items as $item)
                                <tr>
                                    <td>{{$item->location_name}}</td>
                                    <td><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalSchedule{{$item->id}}">Schedule</button></td>
                                    <td><button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalDaysOff{{$item->id}}">Days off</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($items as $item)
    <div class="modal fade" id="modalSchedule{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="createModalTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="createModalTitle">List Schedule ({{$item->location_name}})</h4>
                </div>
                <form action="{{ route(current(explode('.',Route::currentRouteName())) . '.store') }}" enctype="multipart/form-data"  method="POST" role="form">
                    @csrf
                    <input type="hidden" name="submit[location]" value="{{$item->id}}">
                    <input type="hidden" name="submit[type]" value="schedule">
                    <div class="hidden removed_list_schedule">
                        {{-- <input type="hidden" name="submit[removed][]" value="id"> --}}
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Schedule Start</th>
                                    <th>Schedule End</th>
                                    <th>Schedule quota</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['data']['schedule'] as $schedule)
                                    <tr>
                                        <td><input type="text" class="form-control timepicker" value="{{date("g:i A", strtotime($schedule->time_start))}}" data-type="start" data-id="{{$schedule->id}}" name="submit[schedule][old][{{$schedule->id}}][time_start]"></td>
                                        <td><input type="text" class="form-control timepicker" value="{{date("g:i A", strtotime($schedule->time_end))}}" data-type="end" data-id="{{$schedule->id}}" name="submit[schedule][old][{{$schedule->id}}][time_end]"></td>
                                        <td><input type="number" class="form-control" data-type="quota" data-id="{{$schedule->id}}" name="submit[schedule][old][{{$schedule->id}}][quota]" value='{{$schedule->quota}}'></td>
                                        <td><button type="button" class="btn btn-block btn-danger btn-remove-schedule" data-id="{{$schedule->id}}"><i class="fa fa-fw fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                                {{-- @for ($i = 0; $i < 10; $i++)
                                    <tr>
                                        <td><input type="text" class="form-control timepicker" name="submit[schedule][{{$i}}][time-start-start]"></td>
                                        <td><input type="text" class="form-control timepicker" name="submit[schedule][{{$i}}][time-start-end]"></td>
                                        <td><input type="number" class="form-control" name="submit[schedule][{{$i}}][quota]" value='4'></td>
                                    </tr>
                                @endfor --}}
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-block btn-default btn-add-schedule">Add Schedule</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    

    <div class="modal fade" id="modalDaysOff{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="createModalTitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="createModalTitle">List Days Off ({{$item->location_name}})</h4>
                </div>
                <form action="{{ route(current(explode('.',Route::currentRouteName())) . '.store') }}" enctype="multipart/form-data"  method="POST" role="form">
                    @csrf
                    <input type="hidden" name="submit[location]" value="{{$item->id}}">
                    <input type="hidden" name="submit[type]" value="days_off">
                    <div class="hidden removed_list">
                        {{-- <input type="hidden" name="submit[removed][]" value="id"> --}}
                    </div>

                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Days Off</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['data']['days_off'] as $days_off)
                                    <tr>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control pull-right datepicker_dayoff" data-id="{{$days_off->id}}" name="submit[days_off][old][{{$days_off->id}}][date]">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-danger btn-flat btn-remove" data-id="{{$days_off->id}}"><i class="fa fa-fw fa-trash"></i></button>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control pull-right datepicker_dayoff" name="submit[days_off][][date]">
                                            <!-- <input type="text" class="form-control date-picker-range" name="submit[days_off][][date]"> -->
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger btn-flat btn-remove"><i class="fa fa-fw fa-trash"></i></button>
                                            </span>
                                        </div>
                                    </td>
                                </tr> --}}
                                {{-- <tr>
                                    <td><input type="text" class="form-control date-picker-range" name="submit[days_off][{{$i}}][date]"></td>
                                </tr> --}}
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-block btn-default btn-add-dayoff">Add Day Off</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Days Off</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
@endforeach

@endsection	

@section('javascript')
<script>
    function init(){
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('.btn-remove').on('click', function(){
            $parent_remove = $('.removed_list');
            if ($(this).data('id') != null)
                $parent_remove.append('<input type="hidden" name="submit[removed][][id]" value="'+$(this).data('id')+'">');
            
            $(this).parent().parent().parent().parent().remove();
        });

        $('.btn-remove-schedule').on('click', function(){
            $parent_remove = $('.removed_list_schedule')
            if ($(this).data('id') != null)
                $parent_remove.append('<input type="hidden" name="submit[removed][][id]" value="'+$(this).data('id')+'">');

            $(this).parent().parent().remove();
        })

        $('.datepicker_dayoff').datepicker({
            autoclose: true
        });
    }

    function dynamic_init(){
        var id_list = [];

        @foreach ($items as $item)
            @foreach ($item['data']['days_off'] as $day_off)
                $('body').find(".datepicker_dayoff[data-id='{{$day_off->id}}']").datepicker( "setDate", "{{date_format(date_create($day_off->date), 'm/d/Y')}}" );
            @endforeach

           
        @endforeach
    }

    function makeid() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    // console.log(makeid());

    $( document ).ready(function() {
        init();

        dynamic_init();

        $('.btn-add-dayoff').on('click', function(){
            var parent = $(this).parent();
            var table = parent.find('tbody');

            var format = '';
            format += '<tr>';
            format += '    <td>';
            format += '        <div class="input-group input-group-sm">';
            format += '            <input type="text" class="form-control pull-right datepicker_dayoff" name="submit[days_off][new][][date]">';
            format += '            <span class="input-group-btn">';
            format += '                <button type="button" class="btn btn-danger btn-flat btn-remove"><i class="fa fa-fw fa-trash"></i></button>';
            format += '            </span>';
            format += '        </div>';
            format += '    </td>';
            format += '</tr>';

            table.append(format);

            init();
        });

        $('.btn-add-schedule').on('click', function(){
            var parent = $(this).parent();
            var table = parent.find('tbody');

            var id = makeid();

            var format = '';
            format +='<tr>';
            format +='    <td><input type="text" class="form-control timepicker" name="submit[schedule][new]['+id+'][time_start]"></td>';
            format +='    <td><input type="text" class="form-control timepicker" name="submit[schedule][new]['+id+'][time_end]"></td>';
            format +='    <td><input type="number" class="form-control" name="submit[schedule][new]['+id+'][quota]" value="4"></td>';
            format +='    <td><button type="button" class="btn btn-block btn-danger btn-remove-schedule"><i class="fa fa-fw fa-trash"></i></button></td>'
            format +='</tr>';

            table.append(format);

            init();
        })
    });
</script>
@endsection
