@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif

<div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
    <a href="javascript:void(0)" class="fanclub-playlist-box{{$authenticateClass}} {{($authenticateClass == '')? 'playSingleSongInPlayer' : ''}}" {{($authenticateClass == '') ? 'data-song-id='.$songId : ''}}>
        <img src="{{ $icon }} " alt="" />
        <p class="s1">{{ $name }}</p>
    </a>
</div>