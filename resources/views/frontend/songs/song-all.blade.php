@section('title', $seo_title)
@section('metaTitle', $seo_title)
@section('metaKeywords', $seo_meta_keyword)
@section('metaDescription', $seo_description)
@extends('frontend.layouts.master')
@section('content')
@if (Auth::check())
        @php($authenticateClass = '')
    @else
        @php($authenticateClass = ' loginBeforeGo')
    @endif
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
                            {{-- <li><a href="">My Music</a></li> --}}
                            <li>{{ $content->groupDetailData->groupName }}</li>
                        </ul>
                    </div>
                    <div class="header-img-data">
                        <img src="{{ $content->groupDetailData->groupIcon }}" alt="" class="big-img" />
                        <div class="data-wrapper">
                            <span>Playlist</span>
                            <h3>{{ $content->groupDetailData->groupName }}</h3>
                            <span class="blur-color">{{ $content->groupDetailData->countTrack }} Tracks</span>
                            <div class="btn-block">
                                <a href="" class="fill-btn d-none">Play All</a>
                                <form id="myMusicPlayer" method="POST" action="{{ url('/my-music-player') }}">
                                    @csrf
                                    <input type="hidden" name="page" value="dynamic-group">
                                    <input type="hidden" name="slug" value="{{ $slug }}">
                                    <button type="submit" class="fill-btn{{ $authenticateClass }}">Play All</button>
                                </form>
                                <label class="heart">
                                    <input type="checkbox" data-id={{ $content->groupDetailData->groupId }}
                                        class="groupLikeDislike{{ $authenticateClass }}"
                                        {{ $content->groupDetailData->groupLike == 1 ? 'checked' : '' }} value="yes"
                                        name="heart">
                                    <span class="heart-checkmark"></span>
                                </label>
                                @include('frontend.components.action-popup-rounded',['menus' =>
                                ['popupDownload'],'slugForDownloadAll' => $slug, 'pageForDownloadAll' => 'dynamic-group'])

                                <div class="dropdown c-dropdown rounded-dot-menu d-none">
                                    <!-- <button >
                         <img src="assets/img/menu-dot.svg" class="c-icon"/>
                        </button> -->
                                    <a href="" class="more-btn" class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <img src="{{ asset('public/assets/frontend/img/More.svg') }}" alt="" />
                                    </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">
                                            <img src="{{ asset('public/assets/frontend/img/d-img4.png') }}" alt="" />
                                            <span>Download</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- <ul class="nav nav-tabs">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#Favourites">Favourites</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#Downloads">Downloads</a>
                        </li>
                      </ul> -->

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
                            @foreach ($content->groupDetailData->songList as $key => $row)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        <div class="track">
                                            <img src="{{ $row->songIcon }}" alt="" />
                                            <div role="button" class="track-data playSingleSongInPlayer{{ $authenticateClass }}"
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
                                                        class="songLikeDislike{{ $authenticateClass }}"
                                                        {{ $row->songLike == 1 ? 'checked' : '' }} value="yes"
                                                        name="heart">
                                                    <span class="heart-checkmark"></span>
                                                </label>
                                                {{-- <a href="">
                      <img src="{{asset('public/assets/img/table-menu.svg')}}" alt="" />
                    </a> --}}
                                                @include('frontend.components.action-popup',['menus' =>
                                                ['popupAddToPlaylist',
                                                'popupDownload'],'songId' => $row->songId, 'allData' => $row])
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $row->songDuration }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @include('frontend.components.playlist.add.add-to-my-playlist')
    @include('frontend.components.music-player.form-for-single-song')
    @include('frontend.components.music-player.form-for-download-all')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
@endsection
