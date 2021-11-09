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
    <div class="row">
        <div class="col-md-12" style="margin-top: 30px;">
            <div class="box">
                <div class="box-header with-border"><h3 class="box-title">{{ __('Calendar') }}  </h3></div>
              
                <div class="box-body" id="schedule-data" data-schedule="{{ json_encode($schedule) }}" >
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('javascript')
<script> 

   var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
      $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        // right : ''
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      timeFormat: 'H:mm',
      //Random default events
      events    : $("#schedule-data").data("schedule"),
      editable  : false,
      droppable : false, // this allows things to be dropped onto the calendar !!!
    })
</script>
@endsection