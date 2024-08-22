@section('title', $seo_title)
@section('metaTitle', $seo_title)
@section('metaKeywords', $seo_meta_keyword)
@section('metaDescription', $seo_description)
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
                    ALL ARTIST LIST START
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
            <div class="row append-ajax">
                @foreach ($content->artistData as $key => $row)
                    @include('frontend.components.artist.artist-grid', [
                        'detailUrlSlug' => $row->detailUrlSlug,
                        'image' => $row->image,
                        'name' => $row->name,
                    ])
                @endforeach
            </div>
            @if (count($content->artistData) == 12)
                <div class="row">
                    <div class="review-loadmore text-center col-12">
                        <input type="hidden" name="page_no" id="page_no" value="1">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <button class="border-btn clickLoadMore">Load More</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--------------------------
                    ALL ARTIST LIST END
            --------------------------->

@endsection
@section('footscript')
    <script>
        $(document).on('click', '.clickLoadMore', function() {
            var page = $('#page_no').val();
            page = parseInt(page) + 1;
            pageLoadMore(page);
        })

        function pageLoadMore(page) {
            $.ajax({
                url: "{{ Request::url() }}",
                type: "POST",
                data: {
                    page: page,
                    _token: $('#token').val()
                },
                success: function(data) {
                    $('#page_no').val(page);
                    if (!data) {
                       $('.review-loadmore').hide(); 
                    }else{
                        var len = $.grep($.parseHTML(data), function(el, i) {
                            return $(el).hasClass("multi-row")
                        }).length;
                        if (len!=12) {
                            $('.review-loadmore').hide(); 
                        }
                    }
                    $('.append-ajax').append(data);
                }
            });
        }
    </script>
@endsection
