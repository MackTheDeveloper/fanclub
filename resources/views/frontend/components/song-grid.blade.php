@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif
<div class="column">
    <div class="songs-box my-song-box" {{ $authenticateClass == '' ? 'data-song=' . $songId : '' }}>
        @if (!empty($artistId) && Auth::check() && $artistId == Auth::user()->id)
        <div class="dropdown c-dropdown round-drop">
            <button class="dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{url('public/assets/frontend/img/menu-dot.svg')}}" class="c-icon"  alt="" >
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('SongEditView',$songId)}}">
                    <img src="{{url('public/assets/frontend/img/edit.svg')}}" alt="" >
                    <span>edit</span>
                </a>
            </div>
        </div>
        @endif
        <a href="javascript:void(0)" class="img{{$authenticateClass}} {{ $authenticateClass == '' ? 'playSingleSongInPlayer' : '' }}"
            {{ $authenticateClass == '' ? 'data-song-id=' . $songId : '' }}>
            <img src="{{ $icon }}">
        </a>
        <a href="javascript:void(0)" class="img{{$authenticateClass}} {{ $authenticateClass == '' ? 'playSingleSongInPlayer' : '' }}"
            {{ $authenticateClass == '' ? 'data-song-id=' . $songId : '' }}>
            <p class="s1">{{ $name }}</p>
        </a>
        <div class="caption">
            <a href="">
                <p>{{ $artistName }}</p>
            </a>
        </div>
        @if (!isset($hideLikeViews) || $hideLikeViews == '0')
            <div class="views-and-likes">
                <div class="viewer-box">
                    <img src="{{ url('public/assets/frontend/img/aakh.svg') }}" alt="">
                    <p class="caption blur-color">{{ $noViews }}</p>
                </div>
                <div class="liker-box">
                    <img src="{{ url('public/assets/frontend/img/fill-like.svg') }}" alt="">
                    <p class="caption blur-color">{{ $noLikes }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
