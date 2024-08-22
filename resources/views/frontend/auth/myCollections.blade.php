@section('title', 'My Collection')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
                                    MY COLLECTION START
                                --------------------------->

    <div class="my-collection-page">
        <div class="header-gradient">
            <div class="container">
                <div class="header-content">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li><a href="{{route('myplaylist')}}">My Playlists</a></li>
                            <li>{{ $content->fanPlaylistData->playlistName }}</li>
                        </ul>
                    </div>
                    <div class="header-img-data">
                        <img src="{{ $content->fanPlaylistData->playListIcon }}" alt="" class="big-img" />
                        <div class="data-wrapper">
                            <span>Playlist</span>
                            <h3>{{ $playlistName }}</h3>
                            @if ($total)
                                <span class="blur-color">{{ $total }} Tracks</span>
                            @else
                                <span class="blur-color">There are no songs available under this playlist.</span>
                            @endif
                            <div class="btn-block">
                                @php($menus = ['popupEditPlaylist','popupRemovePlaylist'])
                                @if ($total)
                                    @php($menus[] = 'popupDownload')
                                    <a href="" class="fill-btn d-none">Play All</a>
                                    <form id="myMusicPlayer" method="POST" action="{{ url('/my-music-player') }}">
                                        @csrf
                                        <input type="hidden" name="page" value="playlist">
                                        <input type="hidden" name="slug"
                                            value="{{ $content->fanPlaylistData->playlistSlug }}">
                                        <button type="submit" class="fill-btn">Play All</button>
                                    </form>
                                @endif
                                @include('frontend.components.action-popup-rounded',['menus' => $menus,'id' => $fanPlaylistId,'slugForDownloadAll' => $content->fanPlaylistData->playlistSlug, 'pageForDownloadAll' => 'playlist'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($total)    
            <div class="container">
                <div class="tab-content">
                    <div id="Favourites" class="tab-pane active">
                        <div class="my-playlist-table">
                            <table>
                                <tr>
                                    <td>#</td>
                                    <td>Track</td>
                                    <td>Artist</td>
                                    <td>Duration</td>
                                </tr>
                                @foreach ($content->playlistSongsData as $key => $row)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>
                                            <div class="track">
                                                <img src="{{ $row->songIcon }}" alt="" />
                                                <div role="button" class="track-data playSingleSongInPlayer"
                                                    data-song-id={{ $row->songId }}>
                                                    <p class="s1">{{ $row->songName }}</p>
                                                    <span>{{ $row->artistName }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="artist">
                                                <span>{{ $row->artistName }}</span>
                                                <div class="like-menu">
                                                    <label class="heart">
                                                        <input type="checkbox" data-id={{ $row->songId }}
                                                            class="songLikeDislike"
                                                            {{ $row->songLike == 1 ? 'checked' : '' }} value="yes"
                                                            name="heart">
                                                        <span class="heart-checkmark"></span>
                                                    </label>
                                                    @include('frontend.components.action-popup',['menus' =>
                                                    ['popupAddToPlaylist','popupRemoveFromPlaylist',
                                                    'popupDownload'],'songId' => $row->songId,'id' =>
                                                    $row->playListsongIdPk, 'allData' => $row])
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->duration }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif


    </div>
    @include('frontend.components.playlist.add.add-to-my-playlist')
    @include('frontend.components.playlist.remove.remove-from-playlist')
    @include('frontend.components.playlist.edit.edit-fan-playlist')
    @include('frontend.components.music-player.form-for-single-song')
    @include('frontend.components.music-player.form-for-download-all')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
@endsection
