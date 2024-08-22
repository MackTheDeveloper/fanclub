@section('title', 'Music Player')
@extends('frontend.layouts.master')
@section('content')

    <div class="my-playlist-album">
        <div class="container-fluid">
            <div class="row playlist-wrapper">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-7 h-100">
                    <div class="song-album">
                        <div class="song-album-wrapper">
                            <div class="video-thumbnail">
                                @include('frontend.components.music-player.player',['player' =>
                                $content['player'],'playerSong' => $content['playerSong'],'supportMime'=>$supportMime])
                            </div>
                            <div class="music-player-song">
                                @include('frontend.components.music-player.player-song',['playerSong' =>
                                $content['playerSong']])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-5 h-100">
                    <div class="comment-list-wrapper h-100">
                        <div class="lr-carousel h-100">
                            <div class="only-list-wrapper h-100 music-player-queue">
                                @include('frontend.components.music-player.queue',['queueSongs' =>
                                $content['queueSongs'],'page' => $page])
                            </div>
                            <div class="only-review-wrapper h-100 music-player-reviews">
                                @include('frontend.components.music-player.reviews',['songReviews' =>
                                $content['songReviews'],'playerSong' => $content['playerSong']])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('frontend.components.reviews.add.add-review')
    @include('frontend.components.playlist.add.add-to-my-playlist')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/music-controller.js') }}?r=090322" data-base-url="{{ url('/') }}">
    </script>
    <script>
        // console.log(navigator)
        /* $("#sortable").sortable({
            handle: ".drag-handle",
            containment: ".song-list"
        });

        $(document).on('click', '.default-star-box', function() {
            $(".only-list-wrapper").addClass("leftList");
            $(".only-review-wrapper").addClass("upReview")
        })
        $(document).on('click', '.close-review-btn', function() {
            $(".only-list-wrapper").removeClass("leftList");
            $(".only-review-wrapper").removeClass("upReview")
        })


        $(document).on('click', '.download-video', function() {
            if ($('#switch').prop('checked')) {
                window.location.href = $('.songAudioDownload').val();
            } else {
                window.location.href = $('.songVideoDownload').val();
            }
        });

        $(document).on('click', '.playQueueSong', function() {
            var songId = $(this).data('song-id');
            $(this).addClass('activePlaying').siblings().removeClass('activePlaying')
            $.ajax({
                url: '{{ url('get-music-player-data') }}',
                type: 'post',
                data: 'songId=' + songId +
                    '&_token={{ csrf_token() }}',
                success: function(response) {
                    var videoSrc = response.playerSong.playerSongData.data.songVideo;
                    $('video source').attr('src', videoSrc)
                    $('video')[0].load();
                    $('audio').attr('src', response.playerSong.playerSongData.data.songAudio);

                    $('.songAudioDownload').val(response.playerSong.playerSongData.data
                        .songAudioDownload);
                    $('.songVideoDownload').val(response.playerSong.playerSongData.data
                        .songVideoDownload);

                    $('.video-thumbnail .video-likes input').prop('checked', (response.playerSong
                        .playerSongData.data.songLike == 1) ? true : false);
                    $('.video-thumbnail .video-likes input').attr('data-id', response.playerSong
                        .playerSongData.data.songId);
                }
            });


            $.ajax({
                url: "{{ url('get-music-player-song-data') }}",
                type: 'post',
                data: 'songId=' + songId +
                    '&_token={{ csrf_token() }}',
                success: function(response) {
                    $('.music-player-song').html(response)
                }
            });

            $.ajax({
                url: "{{ url('get-music-player-review-data') }}",
                type: 'post',
                data: 'songId=' + songId +
                    '&_token={{ csrf_token() }}',
                success: function(response) {
                    $('.music-player-reviews').html(response)
                }
            });
        }) 


        $(document).ready(function() {
            $(".toggle-current-ellips").click(function() {
                $(this).toggleClass("ellips-add");
            })
        })*/
    </script>
@endsection
