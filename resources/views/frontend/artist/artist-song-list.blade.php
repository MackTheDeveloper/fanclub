@section('title', 'Song List')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
            ARTIST SONG LIST START
    --------------------------->

    <div class="mysongs-page">
        <div class="container">
            <div class="mysongs-pagein">

                <!-- Heading of Sort By -->
                <div class="headof-sortbys">
                    <div class="sortbys-heading">
                        <h5>My Songs</h5>
                        <span>Songs uploaded by you</span>
                    </div>
                    <div class="sortby-update">
                        <p>Sort By</p>
                        <select name="sortby" class="filterWeb">
                            <option value="latest">Most Recent</option>
                            <option value="old">Old</option>
                            <option value="name_asc">A-Z</option>
                            <option value="name_desc">Z-A</option>
                        </select>
                    </div>
                    <div class="filter-header">
                        <div class="d-flex sortIcons">
                            <img src="{{ url('public/assets/img/sortbyicon.png') }}">
                            <span>Sort</span>
                        </div>
                    </div>
                </div>

                <!-- Sort By mobile Menu start  (Open in mobile) -->

                <div class="sortMenu">
                    <p class="s1">Sort By</p>
                    <img src="assets/img/close.svg" class="closeIcons2 fixed-right" alt="close">

                    <div class="sortbar-navigation">
                        <ul class="filterMobile">
                            <li><a class="filter-mob" data-filter="latest" href="javascript:void(0)">Most Recent</a>
                            </li>
                            <li><a class="filter-mob" data-filter="old" href="javascript:void(0)">Old</a></li>
                            <li><a class="filter-mob" data-filter="name_asc" href="javascript:void(0)">A-Z</a></li>
                            <li><a class="filter-mob" data-filter="name_desc" href="javascript:void(0)">Z-A</a></li>
                        </ul>
                    </div>
                </div>

                <!-- sort by Mobile end -->

                <!-- ADD New topic  -->
                <div class="addnew-topicbar">
                    <div class="songsearchbar-withbtn">
                        <div class="starts-newtopic">
                            <div class="header-search">
                                <button>
                                    <img src="{{ url('public/assets/img/search.svg') }}">
                                </button>
                                <input type="text" placeholder="Search Song here.." name="searchSong"
                                    class="searchSong">
                            </div>
                        </div>
                        <div class="uploadssong-btn addtopic-btn">
                            <a href="{{ route('SongUploadView') }}" class="fill-btn plusbtn"><img
                                    src="{{ url('public/assets/frontend/img/btnplus.svg') }}" alt="plusbtn">
                                Upload New Song</a>
                        </div>
                    </div>
                </div>

                <div class="songs-data">
                    <div class="row-5 filteredSongList">
                        @foreach ($content->songFilteredData as $key => $row)
                            @include('frontend.components.song-grid',['songId' => $row->id,'icon' => $row->icon,'name' =>
                            $row->name,'artistName' => $row->artistName,'noViews' => $row->noViews,'noLikes' => $row->noLikes,'artistId' => $row->artistId])
                        @endforeach
                    </div>
                </div>

                <div class="forumlist-loadmore text-center">
                    <input type="hidden" name="page_no" id="page_no" value="1">
                    <button class="border-btn clickLoadMore  mb-5">Load More</button>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------
            ARTIST SONG LIST END
    --------------------------->
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
<script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
</script>
    <script>
        $(document).on('change', '.filterWeb', function() {
            $('.loader-bg').removeClass('d-none');
            sortSearchAjax();
        });
        $(document).on('keyup', '.searchSong', function() {
            sortSearchAjax();
        });

        function sortSearchAjax(page="1",append=0) {
            var search = $('.searchSong').val();
            var sort = $('.filterWeb').val();
            var artistId = {{ $authId }};
            $.ajax({
                url: "{{ route('filterSongs') }}",
                method: 'post',
                data: 'search=' + search + '&page=' + page + '&filter[artist_id]=' + artistId + '&filter[sort]=' + sort +
                    '&_token={{ csrf_token() }}',
                success: function(response) {
                    $('.loader-bg').addClass('d-none');
                    if (append) {
                        var text = response.replace(/<\/?[^>]+(>|$)/g, "");
                        if (text.includes("No Match found")) {
                            $('.clickLoadMore').hide();
                        }else{
                            $('.filteredSongList').append(response);
                        }
                    }else{
                        $('.filteredSongList').html(response);
                    }
                    $('input[name="page_no"]').val(page);
                }
            })
        }
        $(document).on('click', 'a.filter-mob', function() {
            var filter = $(this).data('filter');
            $('select.filterWeb').val(filter);
            sortSearchAjax();
            $('.clickLoadMore').show();
            closeSortPopup()
        })
        $(document).on('click', '.clickLoadMore', function() {
            var pageNo = $('input[name="page_no"]').val();
            // pageNo+=1;
            pageNo=parseInt(pageNo)+1;
            sortSearchAjax(pageNo,1)
        })
    </script>
@endsection
