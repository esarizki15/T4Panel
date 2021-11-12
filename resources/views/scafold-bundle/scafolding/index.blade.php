@extends('layouts.architect.main')
@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="">{{ $caption->menu->name }}</li>
            <li class="active">{{ $caption->name }}</li>
        </ol>
    </section>
    <div class="content">
        <div class="row">
            <div class="col-md-12" style="margin-top: 30px;">
                <div class="main-card card">
                    <div class="card-header with-border">
                        {{ $caption->name }}
                        {{-- <h3 class="card-title">{{ __('Lists') }} {{ $caption->name }}</h3> --}}
                    </div>
                    @if (session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <form method="GET"
                                action="{{ route(current(explode('.', Route::currentRouteName())) . '.index') }}"
                                style="width: 100%;">
                                <div class="col-md-12">
                                    <div class="custom-search-form row">
                                        @if (isset($dateRangeField))
                                            <div class="col-md-2">
                                                <span> <label for="search-by-date">Search By Date</label> <input
                                                        @if (isset($param['search-by-date'])) checked @endif type="checkbox" name="search-by-date"
                                                        id="search-by-date" class="minimal" /> </span>
                                                <input type="text" class="form-control date-picker-range   " name="dtr"
                                                    @if (isset($param['dtr'])) value="{{ $param['dtr'] }}" @endif>
                                            </div>
                                        @endif
                                        <div class="col-md-2 ">
                                            <span> Filter </span>
                                            <select name="fbfl" class="select2 form-control ">
                                                <option value="">CHOOSE</option>
                                                @foreach ($columns as $k => $v)
                                                    @if (isset($columns[$k]) && ($columns[$k]->type == 'hidden' || $columns[$k]->type == 'hidden-list'))
                                                    @else
                                                        @if (isset($columns[$k]) && isset($columns[$k]->filter) && $columns[$k]->filter == false)
                                                        @else
                                                            <option value="{{ $k }}" @if (isset($param['fbfl'])) @if ($k == $param['fbfl']) selected  @endif  @endif>
                                                                {{ strtoupper(implode(' ', explode('_', isset($columns[$k]) && isset($columns[$k]->label) ? $columns[$k]->label : $k))) }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 ">
                                            <span> Search </span>
                                            <input type="text" class="form-control " name="q" placeholder="Search..."
                                                value="{{ $param['q'] }}">
                                        </div>
                                        <div class="col-md-4">
                                            <span> Action </span><br />
                                            <button class="btn-transition btn btn-outline-dark" type="submit">
                                                <i class="fa fa-search"></i> {{ __('Search') }}
                                            </button>
                                            @if ($grantType->CREATE)
                                                <a class="btn btn-info  "
                                                    href="{{ route(current(explode('.', Route::currentRouteName())) . '.create') }}">
                                                    <i class="fa fa-plus"></i> {{ __('Add') }}
                                                </a>
                                            @endif
                                            <a class="btn btn-success  "
                                                href="{{ route(current(explode('.', Route::currentRouteName())) . '.index') }}?export=1&q={{ $param['q'] }}{{ isset($param['search-by-date']) ? '&search-by-date=on' : '' }}&dtr={{ $param['dtr'] }}&fbfl={{ isset($param['fbfl']) ? $param['fbfl'] : '' }}">
                                                <i class="fa fa-download"></i> {{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br />
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        @forelse($items as  $item)
                                            @if ($loop->first)
                                                @foreach (json_decode($item, true) as $k => $v)
                                                    @if ((isset($columns[$k]) && $columns[$k]->type == 'hidden') || (isset($columns[$k]) && $columns[$k]->type == 'textarea') || (isset($columns[$k]) && $columns[$k]->type == 'textarea-wysihtml5') || (isset($columns[$k]) && $columns[$k]->type == 'hidden-list'))
                                                    @else
                                                        <th>{{ strtoupper(implode(' ', explode('_', isset($columns[$k]) && isset($columns[$k]->label) ? $columns[$k]->label : $k))) }}
                                                        </th>
                                                    @endif

                                                @endforeach
                                            @break
                                        @endif
                                        @empty
                                            <td>NO ITEM</td>
                                            @endforelse
                                            @if ($grantType->UPDATE || $grantType->DELETE)
                                                <th>ACTIONS</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $key => $item)
                                            <tr>
                                                @forelse(json_decode($item,true) as $k => $v)

                                                    @if ((isset($columns[$k]) && $columns[$k]->type == 'file') || (isset($columns[$k]) && $columns[$k]->type == 'file-url'))
                                                        @if ($columns[$k]->type == 'file')
                                                            <td style="width:5%">
                                                                <image style="width:100%"
                                                                    src="{{ asset($assetsPath . '/' . $v) }}" />
                                                            </td>
                                                        @endif
                                                        @if ($columns[$k]->type == 'file-url')
                                                            <td style="width:5%">
                                                                <image style="width:100%" src="{{ $v }}" />
                                                            </td>
                                                        @endif
                                                    @else
                                                        @if ((isset($columns[$k]) && $columns[$k]->type == 'hidden') || (isset($columns[$k]) && $columns[$k]->type == 'textarea') || (isset($columns[$k]) && $columns[$k]->type == 'textarea-wysihtml5') || (isset($columns[$k]) && $columns[$k]->type == 'hidden-list'))
                                                        @else
                                                            @if (is_array($v))
                                                                <td>
                                                                    <table>
                                                                        @forelse( $v as $kv => $vv)
                                                                            <tr>
                                                                                <td><strong>{{ $kv }}</strong> </td>
                                                                                @if (is_array($vv))
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
                                                                @if (isset($columns[$k]) && $columns[$k]->type == 'number')
                                                                    <td>Rp. {{ number_format($v) }}</td>
                                                                @else
                                                                    <td>
                                                                        @if (isset($columns[$k]))
                                                                            @if (isset($columns[$k]) && $columns[$k]->type == 'multiple-select')
                                                                                <ul>
                                                                                    @if (is_array(json_decode($v)))
                                                                                        @forelse( json_decode($v) as $kv => $vv)
                                                                                            <li>
                                                                                                @forelse( $columns[$k]->data as  $collection)
                                                                                                    @if (isset($collection->id) && $vv == $collection->id)
                                                                                                        {{ $collection->name }}
                                                                                                    @endif
                                                                                                @empty
                                                                                                    {{ $vv }}
                                                                                                @endforelse
                                                                                            </li>
                                                                                        @empty
                                                                                            <li>
                                                                                                no data
                                                                                            </li>
                                                                                        @endforelse
                                                                                    @else

                                                                                    @endif
                                                                                </ul>
                                                                            @else
                                                                                @forelse( $columns[$k]->data as  $collection)
                                                                                    @if (isset($collection->id) && $v == $collection->id)
                                                                                        {{ $collection->name }}
                                                                                    @endif
                                                                                @empty
                                                                                    @if (isset($columns[$k]) && $columns[$k]->type == 'text-long')
                                                                                        <div
                                                                                            style="width:150px;overflow: auto;height: 60px;">
                                                                                            {{ $v }}</div>
                                                                                    @else
                                                                                        {{ $v }}
                                                                                    @endif
                                                                                @endforelse
                                                                            @endif
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

                                                @if ($grantType->UPDATE || $grantType->DELETE)
                                                    <td>
                                                        <div class="btn-group">
                                                            @if ($grantType->UPDATE) <button type="button" class="btn btn-warning btn-sm" onclick="window.location.href='{{ route(current(explode('.', Route::currentRouteName())) . '.edit', $item->id) }}'"> <i class="fa fa-edit"></i></button> @endif
                                                            @if ($grantType->DELETE)
                                                                <form
                                                                    action="{{ route(current(explode('.', Route::currentRouteName())) . '.destroy', $item->id) }}"
                                                                    method="POST" style="display: inline"
                                                                    onsubmit="return confirm('Are you sure?');">
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    {{ csrf_field() }}
                                                                    <button class="btn btn-danger btn-sm"><i
                                                                            class="fa fa-trash"></i></button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif
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
