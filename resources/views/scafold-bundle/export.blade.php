 <table>
    <thead>
        <tr>
                    @forelse($items as  $item)
                    @if ($loop->first)
                    @foreach(json_decode($item,true) as $k => $v)
                                  @if( isset( $columns[$k]) && $columns[$k]->type == "hidden" || isset( $columns[$k]) && $columns[$k]->type == "textarea"  || isset( $columns[$k]) && $columns[$k]->type == "textarea-wysihtml5"  ) 
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
                @forelse(json_decode($item,true) as $k => $v)
                      
                    @if( isset( $columns[$k]) && $columns[$k]->type == "file" || (isset( $columns[$k]) && $columns[$k]->type == "file-url") ) 
                         <td>{{ $v }}</td>
                    @else 
                              @if( (isset( $columns[$k]) && $columns[$k]->type == "hidden") || (isset( $columns[$k]) && $columns[$k]->type == "textarea")  || (isset( $columns[$k]) && $columns[$k]->type == "textarea-wysihtml5")  ) 
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
                                                    {{ $v }}
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