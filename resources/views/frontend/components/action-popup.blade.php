@php($authRole = getAuthProps())
@if (Auth::check())
        @php($authenticateClass = '')
    @else
        @php($authenticateClass = ' loginBeforeGo')
    @endif
<div class="dropdown c-dropdown my-playlist-dropdown">
    <button class="dropdown-toggle" data-bs-toggle="dropdown">
        <img src="{{ asset('public/assets/frontend/img/menu-dot.svg') }}" class="c-icon" />
    </button>
    <div class="dropdown-menu">
        @if(Auth::check() && $authRole!=2)
        {{-- @if(Auth::check() && Auth::user()->role_id!=2) --}}
            @if (in_array('popupAddToPlaylist', $menus))
                <a class="dropdown-item {{(!empty($refresh) && $refresh)?"refresh-page":""}}" href="javascript:void(0)" value="{{ route('showAddToPlayList', $songId) }}"
                    data-songid='{{ $songId }}' id="btnAddToPlayList">
                    <img src="{{ asset('public/assets/frontend/img/d-img2.png') }}" alt="" />
                    <span>Add to Playlist</span>
                </a>
            @endif
            @if (in_array('popupRemoveFromPlaylist', $menus))
                <a class="dropdown-item" href="javascript:void(0)"
                    value="{{ route('showRemoveFromPlaylist', ['removeSongFromPlaylist', $id]) }}" data-songid=''
                    id="btnRemoveFromPlayList">
                    <img src="{{ asset('public/assets/frontend/img/delete.svg') }}" alt="" />
                    <span>Remove from Playlist</span>
                </a>
            @endif
        @endif
        @if (in_array('popupDownload', $menus))
            <a class="dropdown-item blobVideoUrlDownload{{$authenticateClass}}"
                href="{{ isset($allData) && isset($allData->songVideoDownload) ? $allData->songVideoDownload : 'javascript:void(0)' }}">
                <img src="{{ asset('public/assets/frontend/img/d-img4.png') }}" alt="" />
                <span>Download</span>
            </a>
        @endif
    </div>
</div>
