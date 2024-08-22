@section('title','Upload Song')
@extends('frontend.layouts.master')
@section('content')
<!-- My Reviews Songs Page starts here -->
<div class="mymusic-page">
        <div class="container-fluid">
            <div class="mymusic-pagein">
                <div class="mymusic-topicbar">
                    <div class="row">
                        <div class="col-md-6 col-7">
                            <div class="starts-newtopic">
                                <a href="javascript:void(0)" class="fill-btn plusbtn addPlaylistModal">
                                    <img src="{{asset('public/assets/frontend/img/btnplus.svg')}}" alt="plusbtn">Create Playlist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="new-on-fanclub">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>{{$content['myPlaylist']->title}}</h5>
                    <a href="{{route('myplaylist')}}" class="a">See All</a>
                </div>
            </div>
            <div class="square-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach($content['myPlaylist']->myPlaylistData as $key=>$row)
                    <a href="{{route('my-playlist',$row->playlistSlug)}}" class="item">
                        <img src="{{$row->playListIcon}}">
                        <p class="s1">{{$row->playlistName}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="new-on-fanclub">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>{{$content['favPlaylist']->title}}</h5>
                    <a href="{{route('favourite-playlist')}}" class="a">See All</a>
                </div>
            </div>
            <div class="square-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach($content['favPlaylist']->favPlaylistData as $key=>$row)
                    <a href="{{route('songCollection',$row->groupSlug)}}" class="item">
                        <img src="{{$row->groupIcon}}">
                        <p class="s1">{{$row->groupName}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="featur-artist">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>{{$content['favArtist']->title}}</h5>
                    <a href="{{route('myArtists')}}" class="a">See All</a>
                </div>
            </div>
            <div class="rounded-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach($content['favArtist']->favArtistData as $key=>$row)
                    <a href="{{route('artistDetail',$row->artistSlug)}}" class="item">
                        <img src="{{$row->artistProfilePic}}">
                        <p class="s1">{{$row->artistFirstName.' '.$row->artistLastName}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="new-on-fanclub">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>{{$content['myCollections']->title}}</h5>
                    <a href="{{route('my-favourite')}}" class="a">See All</a>
                </div>
            </div>
            <div class="square-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach($content['myCollections']->myCollectionsData as $key=>$row)
                    <a href="javascript:void(0)" class="item playSingleSongInPlayer" data-song-id="{{$row->songId}}">
                        <img src="{{$row->songIcon}}">
                        <p class="s1">{{$row->songName}}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @include('frontend.components.my-music.add-to-playlist-modal')
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
<script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}"></script>
<script type="text/javascript">
    // addToPlaylistModal
    $(document).on('click','.addPlaylistModal',function(){
        $('#addToPlaylistModal').modal('show');
    })

    $("#formAddToPlaylist").validate({
        ignore: [],
        rules: {
            playlist_name: 'required',
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#addToPlaylistModal').modal('hide');
                    if (response.statusCode == '200') {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        setTimeout(function(){
                            toastr.clear();
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            });
        }
    });
</script>
@endsection