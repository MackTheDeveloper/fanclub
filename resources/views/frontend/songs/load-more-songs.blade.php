@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif
@foreach ($content->songData->songsDetails as $key => $row)
    <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
        <a href="javascript:void(0)" class="fanclub-playlist-box{{$authenticateClass}} playSingleSongInPlayer"
            data-song-id={{ $row->songId }}>
            <img src="{{ $row->icon }} " alt="" />
            <p class="s1">{{ $row->name }}</p>
        </a>
    </div>
@endforeach
