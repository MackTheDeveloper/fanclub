@section('title', $content->genredata->seo_title)
@section('metaTitle', $content->genredata->seo_title)
@section('metaKeywords', $content->genredata->seo_meta_keyword)
@section('metaDescription', $content->genredata->seo_description)
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
                Favourite Playlists START
            --------------------------->

    <div class="top-artist-page">
        <div class="container">
            <div class="breadCrums">
                <ul>
                    <li><a href="{{ url('/') }}">fanclub</a></li>
                    <li>{{ $title }}</li>
                </ul>
            </div>
            <h4>{{ $title }}</h4>
            @if ($content->songData->data)
                <div class="row append-items">
                    @foreach ($content->songData->data as $key => $row)
                        @include('frontend.components.song-grid-2',['songId' => $row->songId,'icon' => $row->songIcon,'name' => $row->songName])
                    @endforeach
                </div>
            @else
                <div class="no-data-found">
                    <div class="container-fluid">
                        <h6 class="text-center">No results found for your search criteria.</h6>
                    </div>
                </div>
            @endif
        </div>

        @if ($content->songData->data && count($content->songData->data) > 5)
            <div class="review-loadmore text-center">
                <input type="hidden" name="categoryId" id="categoryId" value="{{ $content->categoryId }}">
                <input type="hidden" name="page_no" id="page_no" value="{{ $content->pageNo }}">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <button class="border-btn clickLoadMore">Load More</button>
            </div>
        @endif
    </div>
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
<script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}"></script>
<script>
    $(document).on('click', '.clickLoadMore', function() {
        var page = $('#page_no').val();
        var categoryId = $('#categoryId').val();
        let new_page = parseInt(page) + 1;
        loadMoreContent(new_page, categoryId);
    })

    function loadMoreContent(page, categoryId) {
        $.ajax({
            url: "{{ route('categoryLoadMore') }}",
            method: "POST",
            data: {
                'page': page,
                "_token": $('#token').val(),
                'categoryId': categoryId,
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
