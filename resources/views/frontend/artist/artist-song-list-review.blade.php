@section('title', 'Reviews')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
            ARTIST SONG REVIEW START
    --------------------------->
    <!-- My Reviews Page starts here -->
    <div class="mysongs-reviewpage">
        <div class="container">
            <div class="mysongsreview-pagein">
                <!-- breadcrumb Component is here    -->
                <div class="breadcrumb-section">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li>Reviews of My Songs</li>
                        </ul>
                    </div>
                </div>

                <!-- Heading of Sort By -->
                <div class="headof-sortbys">
                    <div class="sortbys-heading">
                        <h5>Reviews of My Songs</h5>
                        <span>Select a song for your reviews</span>
                    </div>
                </div>

                <!-- Sort By mobile Menu start  (Open in mobile) -->

                {{-- <div class="sortMenu">

                    <p class="s1">Sort By</p>
                    <img src="{{asset('public/assets/frontend/img/close.svg')}}" class="closeIcons2 fixed-right" alt="close">

                    <div class="sortbar-navigation">
                        <ul>
                            <li><a href="javascript:void(0)">Recently Played</a></li>
                            <li><a href="javascript:void(0)">Most Recent</a></li>
                            <li><a href="javascript:void(0)">On Sale</a></li>
                            <li><a href="javascript:void(0)">Price: Low to High</a></li>
                            <li><a href="javascript:void(0)">Price: High to Low</a></li>
                        </ul>
                    </div>
                </div> --}}

                <!-- sort by Mobile end -->

                <!-- ADD New topic  -->


                <div class="addnew-topicbar">
                    <div class="songsearchbar-withbtn">
                        <div class="starts-newtopic w-100">
                            <div class="header-search">
                                <button>
                                    <img src="{{ asset('public/assets/frontend/img/search.svg') }}">
                                </button>
                                <input class="searchSong" type="text" placeholder="Search Song here" name="">
                            </div>
                        </div>
                        {{-- <div class="sortby-update">
                            <p>Sort By</p>
                            <select>
                                <option>Recently Played</option>
                                <option>Most Recent</option>
                                <option>On Sale</option>
                                <option>Price: Low to High</option>
                            </select>
                        </div>
                        <div class="filter-header">
                            <div class="d-flex sortIcons">
                                <img src="assets//img/sortbyicon.png">
                                <span>Sort</span>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="songs-data">
                <div class="row-5 filteredSongList">
                    @foreach ($content->songFilteredData as $key => $row)
                        @include('frontend.components.song-grid',['songId' => $row->id,'icon' => $row->icon,'name' =>
                        $row->name,'artistName' =>
                        $row->artistName,'noViews' => $row->noViews,'noLikes' => $row->noLikes,'hideLikeViews' => 0])
                        {{-- <div class="column">
                        <a href="javascript:void(0)" class="songs-box" data-song="{{$row->id}}">
                            <img src="{{$row->icon}}">
                            <p class="s1">{{$row->name}}</p>
                            <div class="caption">
                                <p>{{$row->artistName}}</p>
                            </div>
                        </a>
                    </div> --}}
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!--------------------------
            ARTIST SONG REVIEW END
    --------------------------->
@endsection
@section('footscript')
    <script type="text/javascript">
        {{-- {{ route('artistSongReview',$row->id) }} --}}
        $(document).on('click', '.column .songs-box', function() {
            var songId = $(this).data('song');
            window.location.href = "{{ url('/song-review') }}/" + songId
        })

        //$(document).on('change keyup','.searchSong',function(){
        $(document).on('keyup', '.searchSong', function() {
            var value = $(this).val();
            $.ajax({
                url: "{{ route('filterArtistSongsReview') }}",
                method: 'post',
                data: 'search=' + value + '&_token={{ csrf_token() }}',
                success: function(response) {
                    $('.filteredSongList').html(response);
                }

            })
        });
    </script>
@endsection
