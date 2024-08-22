@foreach($content->songData->data as $key=>$row)
    @include('frontend.components.song-grid-2',['songId' => $row->songId,'icon' => $row->songIcon,'name' => $row->songName])
@endforeach