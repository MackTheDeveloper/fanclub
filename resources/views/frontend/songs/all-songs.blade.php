@section('title', 'Songs')
@extends('frontend.layouts.master')
@section('content')
@if (Auth::check())
    @php($authenticateClass = '')
@else
    @php($authenticateClass = ' loginBeforeGo')
@endif
    <!--------------------------
            Favourite Playlists START
        --------------------------->

    <div class="top-artist-page">
        <div class="container">
            <div class="breadCrums">
                <ul>
                    <li><a href="#">fanclub</a></li>
                    <li>{{ $title }}</li>
                </ul>
            </div>
            <h4>{{ $title }}</h4>
            <div class="row append-items">
                @foreach ($content->songData->songsDetails as $key => $row)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2">
                        <a href="javascript:void(0)" class="fanclub-playlist-box{{$authenticateClass}} playSingleSongInPlayer"
                            data-song-id={{ $row->songId }}>
                            <img src="{{ $row->icon }} " alt="" />
                            <p class="s1">{{ $row->name }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="review-loadmore text-center">
        <input type="hidden" name="page_no" id="page_no" value="{{ $content->pageNo }}">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <button class="border-btn clickLoadMore">Load More</button>
    </div>
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
    <script>
        $(document).on('click', '.clickLoadMore', function() {
            var page = $('#page_no').val();
            let new_page = parseInt(page) + 1;
            loadMoreContent(new_page);
        })

        function loadMoreContent(page) {
            $.ajax({
                url: "{{ route('songLoadMore') }}",
                method: "POST",
                data: {
                    'page': page,
                    "_token": $('#token').val(),
                },

                success: function(response) {
                    if (response) {
                        $('.append-items').append(response);
                        $('#page_no').val(page);
                    } else {
                        $('.clickLoadMore').hide();
                    }
                }
            })
        }
    </script>
@endsection
