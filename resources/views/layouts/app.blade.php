@guest

@yield('login-form')

@else
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'One Smile Panel') }}</title>
        
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/layout/fontawesome.css') }}">
        <!-- Styles -->
        <link href="{{ asset('css/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/layout/admin.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/layout/_all-skins.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/select2/dist/css/select2.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('css/Ionicons/css/ionicons.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('css/datatables/core.css') }}">
        <!-- bootstrap datepicker -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{ asset('css/iCheck/all.css') }}">
        <!-- daterange picker -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap-daterangepicker/daterangepicker.css') }}">
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{ asset('css/jvectormap/jquery-jvectormap-1.2.2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fullcalendar/dist/fullcalendar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fullcalendar/dist/fullcalendar.print.min.css') }}" media="print">
    </head>
    <body class="hold-transition skin-purple-light sidebar-mini">
        <div class="wrapper" id="app">
            
            <header class="main-header">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">{{ config('app.name', 'Cerberus') }}</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>{{ config('app.name', 'CER') }}</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Notifications: style can be found in dropdown.less -->
                              <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                  <i class="fa fa-bell-o"></i>
                                  <span class="label label-warning">10</span>
                                </a>
                                <ul class="dropdown-menu">
                                  <li class="header">You have 10 notifications</li>
                                  <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                      <li>
                                        <a href="#">
                                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#">
                                          <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                                          page and may cause design problems
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#">
                                          <i class="fa fa-users text-red"></i> 5 new members joined
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#">
                                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#">
                                          <i class="fa fa-user text-red"></i> You changed your username
                                        </a>
                                      </li>
                                    </ul>
                                  </li>
                                  <li class="footer"><a href="#">View all</a></li>
                                </ul>
                              </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="user user-menu">
                                <a href="#">
                                    <span class="hidden-xs"> {{ Auth::user()->name }}</span> 
                                </a>
                                
                            </li>
                            
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off"></i></a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                   @forelse($navs as $nav)
                          <ul class="sidebar-menu" data-widget="tree">
                              <li class="header">{{ $nav->name }}</li>
                            @forelse($nav->menu->where('status',true) as $menu)
                                <li class="treeview @if ($caption->menu->code  == $menu->code ) {{ 'active' }} @endif  ">
                                    <a href="#">
                                       <i class="fa {{ $menu->icon}} "></i>
                                        <span>{{ $menu->name }} </span>
                                        <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>
                                     <ul class="treeview-menu">
                                        @forelse($menu->submenu->where('status',true) as $submenu)
                                            <li class= " @if ($caption->code  == $submenu->code ) {{ 'active' }} @endif  ">
                                                    <a href="@if($submenu->path) {{ route($submenu->path) }} @else {{ '/' }} @endif"><i class="fa fa-circle-o"></i> {{ $submenu->name }}</a>
                                            </li>
                                        @empty
                                        @endforelse
                                    </ul>
                                </li>
                            @empty

                            @endforelse
                          </ul>
                    @empty

                    @endforelse


                  <!--      
                <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class=" @if ($caption->code  == 'dashboard' ) {{ 'active' }} @endif   ">
                            <a href="{{ url('/home') }}">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                                
                            </a>
                            
                        </li>
                     <li class="">
                            <a href="#">
                                <i class="fa fa-feed"></i> <span>Promo</span>
                            </a>
                            
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-shopping-cart"></i> <span>Order & Invoice</span>
                            </a>
                            
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-credit-card"></i> <span>Payment</span>
                            </a>
                            
                        </li>
                        <li class="">
                            <a href="#">
                                <i class="fa fa-file-o"></i> <span>Voucher</span>
                            </a>
                            
                        </li>
                    </ul>
 
                 -->
                 
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/jquery/dist/jquery.min.js') }}"  ></script>
    <script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}"  ></script>
    <!-- Select2 -->
    <script src="{{ asset('css/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('css/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('js/datatables/core.js') }}"></script>
    <script src="{{ asset('js/layout/admin.min.js') }}"  ></script>
    <script src="{{ asset('js/moment/min/moment.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('css/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('css/iCheck/icheck.min.js') }}"></script>
    
    <!-- jvectormap  -->
    <script src="{{ asset('css/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('css/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('js/chart.js/Chart.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('css/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('css/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('css/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('css/fullcalendar/dist/fullcalendar.min.js') }}"></script>

    <script src="{{ asset('js/layout/core.js') }}"  ></script>
    @yield('javascript')
</body>
</html>
@endguest