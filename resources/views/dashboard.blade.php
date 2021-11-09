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

  <table class="table table-bordered ">
                        <thead>
                            <tr>
                                
                                <th>ID</th>
                                <th>NAME</th>
                                <th>SLUG</th>
                                <th>DOWN PAYMENT</th>
                                <th>DEPARTURE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $key => $item)
                            <tr>
                                <td width="5%">{{ $item->id }}</td>
                                <td width="35%">{{ $item->name }}</td>
                                <td width="35%">{{ $item->slug }}</td>
                                <td width="15%">{{ $item->down_payment_price }}</td>
                                <td width="15%">{{ $item->departure->count() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td>No entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>



                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>SLUG</th>
                                <th>DOWN PAYMENT</th>
                                <th>DEPARTURE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($itin as $key => $item)
                            <tr>
                                @forelse(json_decode($item,true) as $k => $v)
                                <td>{{ $v }}</td>
                                @empty
                                <td>&nbsp;</td>
                                @endforelse
                                
                            @empty
                            <tr>
                                <td>No entries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
@endsection
