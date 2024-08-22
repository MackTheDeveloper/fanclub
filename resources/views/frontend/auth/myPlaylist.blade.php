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
                            @if (!empty($search))
                                <li><a href="{{ route('searchFront', $search) }}">search</a></li>
                            @endif
                            <li>{{$title?:"My Collection"}}</li>
                        </ul>
                    </div>
                    <div class="header-img-data">
                        <img src="{{ asset('public/assets/img/my-collection-banner.png') }}" alt=""
                            class="big-img" />
                        <div class="data-wrapper">
                            <h3>{{$title?:"My Collection"}}</h3>
                            <span class="blur-color">{{ $total }} Tracks</span>
                            <div class="btn-block">
                                <a href="" class="fill-btn d-none">Shuffle Play</a>
                                <form id="myMusicPlayer" method="POST" action="{{ url('/my-music-player') }}">
                                    @csrf
                                    <input type="hidden" name="page" value="{{$page}}">
                                    <input type="hidden" name="slug" value="">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <button type="submit" class="fill-btn">Play All</button>
                                </form>
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
                            @foreach ($content->favSongsData as $key => $row)
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
                                                ['popupAddToPlaylist','popupDownload'],'songId' => $row->songId, 'allData' => $row])
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


    </div>
    @include('frontend.components.playlist.add.add-to-my-playlist')
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
@endsection
