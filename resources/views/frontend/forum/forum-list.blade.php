@section('title', $seo_title)
@section('metaTitle', $seo_title)
@section('metaKeywords', $seo_meta_keyword)
@section('metaDescription', $seo_description)
@extends('frontend.layouts.master')
@section('content')


    <div class="forumlisting-page">
        <div class="container">
            <div class="forumlisting-pagein">
                <!-- breadcrumb Component is here    -->
                <div class="breadcrumb-section">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li>Forums</li>
                        </ul>
                    </div>
                </div>

                <!-- Heading of Sort By -->
                <div class="headof-sortbys">
                    <div class="sortbys-heading">
                        <h5>Welcome to fanclub Forum</h5>
                        <span>Generate a discussion or share your thoughts, our artists and fans want to hear from you.</span>
                    </div>
                    <div class="sortby-update">
                        <p>Sort By</p>
                        <select class="filterWeb">
                            <option value="latest">Most Recent</option>
                            <option value="old">Old</option>
                            <option value="liked_desc">Most helpful</option>
                            <option value="name_asc">A-Z</option>
                            <option value="name_desc">Z-A</option>

                        </select>
                    </div>
                    <div class="filter-header">
                        <div class="d-flex sortIcons">
                            <img src={{ url('public/assets/frontend/img/sortbyicon.png') }}>
                            <span>Sort</span>
                        </div>
                    </div>
                </div>

                <!-- Sort By mobile Menu start  (Open in mobile) -->

                <div class="sortMenu">

                    <p class="s1">Sort By</p>
                    <img src="{{ url('public/assets/frontend/img/close.svg') }}" class="closeIcons2 fixed-right"
                        alt="close">

                    <div class="sortbar-navigation">
                        <ul>
                            <li><a class="filter-mob" data-filter="latest" href="javascript:void(0)">Most Recent</a>
                            </li>
                            <li><a class="filter-mob" data-filter="old" href="javascript:void(0)">Old</a></li>
                            <li><a class="filter-mob" data-filter="liked_desc" href="javascript:void(0)">Most
                                    helpful</a></li>
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
                                    <img src={{ url('public/assets/frontend/img/search.svg') }}>
                                </button>
                                <input type="text" class="form-controller" id="search" name="search"
                                    placeholder="Search topic here" />
                                <!-- <input type="text" type="text" id="name" name="name" placeholder="Search topic here" > -->
                            </div>
                        </div>
                        @if (Auth::check())
                            <div class="uploadssong-btn addtopic-btn">
                                <a href="" class="fill-btn plusbtn" data-toggle="modal" data-target="#newTopicModal">
                                    <img src="{{ url('public/assets/frontend/img/btnplus.svg') }}" alt="plusbtn">
                                    Start New Topic
                                </a>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="forum-listing-content">
                    <div class="forum-iteams-main">
                        <div class="append-items">
                            @foreach ($content['forumList']->ForumListData->list as $key => $value)
                                <div class="forum-list-iteam">
                                    <div class="forum-likeshits">
                                        @if (!Auth::check())
                                            <img src="{{ url('public/assets/frontend/img/heart.png') }}" width=""
                                                height="" alt="" />
                                        @else
                                            <label class="heart heart-big">
                                                <input type="checkbox" value="yes" data-id="{{ $value->id }}"
                                                    class="forumLikedDisliked" name="heart"
                                                    {{ $value->liked == 1 ? 'checked' : '' }}>
                                                <span class="heart-checkmark"></span>
                                            </label>
                                        @endif
                                        <span id="checkvalue">{{ $value->likes }}</span>

                                    </div>
                                    <div class="forum-list-content toggle-parent">
                                        <a href="{{ route('forumdetail', $value->id) }}">
                                            <p class="s1">{{ $value->topic }}</p>
                                        </a>
                                        {{-- <span> {{$value->description}}</span> --}}
                                        <p class="blur-color toggle-content toggle-apply-2"> {!! nl2br(e($value->description)) !!}</p>
                                        <a href="javascript:void(0)" class="a toggle-btns">Read More</a>
                                        <div class="forums-authorsmain">
                                            <div class="forumauthor-details">
                                                <img src="{{ $value->createdByImage }}" />
                                                <span class="forumauth-titles"> {{ $value->createdByName }}</span>
                                                <span class="forumauth-dot"></span>
                                                <span class="forumauth-time">{{getFormatedDateForWeb($value->createdAt)}}</span>
                                            </div>
                                            <div class="forums-detail-btn">
                                                <img src={{ url('public/assets/frontend/img/forum-msg.svg') }} width=""
                                                    height="" alt="" />
                                                <span>{{ $value->comments }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="forumlist-loadmore">
                            <input type="hidden" name="page_no" id="page_no" value="{{ $content['forumList']->pageNo }}">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <button class="border-btn clickLoadMore">Load More</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if (Auth::check())
        <div class="modal fade addNewsPopup" id="newTopicModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Start New Topic</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><img
                                    src={{ url('public/assets/frontend/img/cancel-popup.svg') }} /></span>
                        </button>
                    </div>
                    <form id="forum-add" class="modal-body">

                        <div class="inputs-group">
                            <input id="topic" name="topic" type="text" />
                            <span>Topic*</span>
                        </div>
                        <div class="inputs-group">
                            <textarea id="desc" name="desc"></textarea>
                            <span>Description*</span>
                        </div>
                        <input id="" type="hidden" />
                        <div class="m-footer">
                            <button class="fill-btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('footscript')

    <script>
        $(document).on('click', '.clickLoadMore', function() {
            $('.loader-bg').removeClass('d-none');
            var page = $('#page_no').val();
            let new_page = parseInt(page) + 1;
            ajaxSubmitData(new_page);
            // loadMoreContent(new_page);
        });

        $(document).on('change', 'select.filterWeb', function() {
            $('.loader-bg').removeClass('d-none');
            ajaxSubmitData(1, 1);
            $('.clickLoadMore').show();
        });
        $('#search').on('keyup', function() {
            ajaxSubmitData(1, 1);
            $('.clickLoadMore').show();
        });

        function ajaxSubmitData(page, html = "0") {
            var filterData = $('select.filterWeb').val();
            var search = $('#search').val();
            $.ajax({
                url: "{{ route('forumAjaxReq') }}",
                method: "POST",
                data: {
                    'page': page,
                    'filter': filterData,
                    'search': search,
                    "_token": $('#token').val(),
                },
                success: function(response) {
                    if (response) {
                        if (html == "1") {
                            $('.append-items').html(response);
                        } else {
                            $('.append-items').append(response);
                        }
                        // $('.append-items').append(response);
                        $('.loader-bg').addClass('d-none');
                        $('#page_no').val(page);
                    } else {
                        if (html == "1") {
                            $('.append-items').html('<p class="text-center">No search Data found</p>');
                        }
                        $('.loader-bg').addClass('d-none');
                        $('.clickLoadMore').hide();
                    }
                    multiLineOverflows()
                }
            });
        }



        $("#forum-add").validate({
            ignore: [],
            rules: {
                topic: "required",
                desc: "required",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var topic = $('#topic').val();
                var desc = $('#desc').val();
                $.ajax({
                    url: "{{ route('forumCreate') }}",
                    method: "POST",
                    data: {
                        'topic': topic,
                        'desc': desc,
                        "_token": $('#token').val()
                    },
                    success: function(response) {
                        if (response.success == true) {
                            window.location.reload();
                        }
                    },
                });
            }
        });

        $(document).on('change', '.forumLikedDisliked', function() {
            var forumId = $(this).data('id');
            var thisis = $(this);
            if (forumId) {
                $.ajax({
                    url: "{{ route('forumLikeDislike') }}",
                    method: 'post',
                    data: 'f_id=' + forumId + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        // window.location.reload();
                        thisis.closest('.forum-likeshits').find('#checkvalue').text(response.component
                            .no_likes)
                    }
                })
            }
        });

        $(document).on('click', 'a.filter-mob', function() {
            var filter = $(this).data('filter');
            $('select.filterWeb').val(filter);
            ajaxSubmitData(1, 1);
            $('.clickLoadMore').show();
            closeSortPopup()
        })

        $(document).ready(function(){
            multiLineOverflows();
        })
    </script>

@endsection
