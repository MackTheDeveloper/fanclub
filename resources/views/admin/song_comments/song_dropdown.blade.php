@foreach($data as $key=>$row)
    <option value="{{$row->id}}">{{$row->name}}</option>
@endforeach