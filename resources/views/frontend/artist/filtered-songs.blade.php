@if(count($content->songFilteredData))
@foreach($content->songFilteredData as $key=>$row)
    @include('frontend.components.song-grid',['songId' => $row->id,'icon' => $row->icon,'name' => $row->name,'artistName' => $row->artistName,'noViews' => $row->noViews,'noLikes' => $row->noLikes])
@endforeach
@else
<p class="text-center">No Match found</p>
@endif