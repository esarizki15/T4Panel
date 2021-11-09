@extends('layouts.app')

@section('content')
 <section class="content-header">
      <h1>
        Dashboard
        <small>You are logged in!</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

  <section class="content">
<!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12">
          Summary Data 
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3> <span class="" style="font-size: 25px" > 0 <span style="font-size: 10px">Active User </span> / 0 <span style="font-size: 10px">Registered User </span></span></h3>

              <p>Member</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>  <span class="" style="font-size: 25px" >  0<span style="font-size: 10px">paid </span> / 0<span style="font-size: 10px">Not Paid</span></span> </h3>

              <p>Number Of Transaction </p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-balance-scale"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3> <span class="" style="font-size: 25px" > 0<span style="font-size: 10px">cash-in </span>  / 0<span style="font-size: 10px">cash-left</span>  </span> </h3>

              <p>Revenue Amount</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-clipboard"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3> <span class="" style="font-size: 25px" > 0 <span style="font-size: 10px">Active </span> / 0 <span style="font-size: 10px">Not Active </span></span></h3>

              <p>Market Place</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-dollar"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Paid Activity Transaction Report</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i></button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center">
                    <strong>Sales: 1 Jan, 2014 - 30 Jul, 2014</strong>
                  </p>

                  <div class="chart">
                    <!-- Sales Chart Canvas -->
                    <canvas id="paidActivityChart" style="height: 180px;"></canvas>
                  </div>
                  <!-- /.chart-responsive -->
                </div>
                
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                    <h5 class="description-header">$35,210.43</h5>
                    <span class="description-text">TOTAL REVENUE</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                    <h5 class="description-header">$10,390.90</h5>
                    <span class="description-text">TOTAL COST</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 col-xs-6">
                  <div class="description-block border-right">
                    <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                    <h5 class="description-header">$24,813.53</h5>
                    <span class="description-text">TOTAL PROFIT</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
<!-- /.info-box -->
 </section>
@endsection

@section('javascript')
    <script type="text/javascript">
           
        var paidActivityChartData = {
          labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
          datasets: [
            {
              label               : 'Electronics',
              fillColor           : 'rgb(210, 214, 222)',
              strokeColor         : 'rgb(210, 214, 222)',
              pointColor          : 'rgb(210, 214, 222)',
              pointStrokeColor    : '#c1c7d1',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgb(220,220,220)',
              data                : [65, 59, 80, 81, 56, 55, 40]
            },
            {
              label               : 'Digital Goods',
              fillColor           : 'rgba(60,141,188,0.9)',
              strokeColor         : 'rgba(60,141,188,0.8)',
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : [28, 48, 40, 19, 86, 27, 90]
            }
          ]
        };
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection