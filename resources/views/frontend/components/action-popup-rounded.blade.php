@if (Auth::check())
        @php($authenticateClass = '')
    @else
        @php($authenticateClass = ' loginBeforeGo')
    @endif
<div class="dropdown c-dropdown rounded-dot-menu">
    <a href="" class="more-btn" class="dropdown-toggle" data-bs-toggle="dropdown">
        <img src="{{ asset('public/assets/frontend/img/More.svg') }}" />
    </a>
    <div class="dropdown-menu">
        @if (in_array('popupEditPlaylist', $menus))
            <a class="dropdown-item" href="javascript:void(0)" value="{{ route('showEditFanPlaylist', $id) }}"
                id="btnEditFanPlayList">
                <img src="{{ asset('public/assets/frontend/img/edit.svg') }}" alt="" />
                <span>Edit Playlist</span>
            </a>
        @endif
        @if (in_array('popupRemovePlaylist', $menus))
            <a class="dropdown-item" href="javascript:void(0)"
                value="{{ route('showRemoveFromPlaylist', ['removePlaylist', $id]) }}" id="btnRemoveFromPlayList">
                <img src="{{ asset('public/assets/frontend/img/delete.svg') }}" alt="" />
                <span>Remove Playlist</span>
            </a>
        @endif
        @if (in_array('popupDownload', $menus))
            <a class="dropdown-item download-all{{$authenticateClass}}" href="javascript:void(0)" data-slug={{$slugForDownloadAll}} data-page={{$pageForDownloadAll}}>
                <img src="{{ asset('public/assets/frontend/img/d-img4.png') }}" alt="" />
                <span>Download</span>
            </a>
        @endif
    </div>
</div>
