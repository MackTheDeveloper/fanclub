@section('title', 'Forum Details')
@extends('frontend.layouts.master')
@section('content')
    <div class="backBg"></div>
    <!-- My Reviews Page starts here -->
    <div class="forumlisting-page">
        <div class="container">
            <div class="forumlisting-pagein">

                <!-- breadcrumb Component is here    -->
                <div class="breadcrumb-section">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li><a href="{{ route('forumsList') }}">Forums</a></li>
                            <li>{{ $content['forumDetails']->forumDetailsData->title }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Heading of Sort By -->
                {{-- <div class="headof-sortbys">
                    <div class="sortbys-heading">
                        <h5>Welcome to fanclub Forum</h5>
                        <span>Generate a discussion or share your thoughts, our artists and fans want to hear from you.</span>
                    </div>
                    <div class="sortby-update">
                        <p>Sort By</p>
                        <select>
                            <option>All</option>
                            <option>Most Recent</option>
                            <option>On Sale</option>
                            <option>Price: Low to High</option>
                        </select>
                    </div>
                    <div class="filter-header">
                        <div class="d-flex sortIcons">
                            <img src="{{url('public/assets/img/sortbyicon.png')}}">
                            <span>Sort</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Sort By mobile Menu start  (Open in mobile) -->

                {{-- <div class="sortMenu">

                    <p class="s1">Sort By</p>
                    <img src="{{url('public/assets/img/close.svg')}}" class="closeIcons2 fixed-right" alt="close">

                    <div class="sortbar-navigation">
                        <ul>
                            <li><a href="#">All</a></li>
                            <li><a href="#">Most Recent</a></li>
                            <li><a href="#">On Sale</a></li>
                            <li><a href="#">Price: Low to High</a></li>
                            <li><a href="#">Price: High to Low</a></li>
                        </ul>
                    </div>
                </div> --}}
                <!-- sort by Mobile end -->
                <!-- ADD New topic  -->
                <div class="addnew-topicbar">
                    <div class="songsearchbar-withbtn">
                        <div class="starts-newtopic">
                            <div class="header-search">
                                <button>
                                    <img src="{{ url('public/assets/img/search.svg') }}">
                                </button>
                                {{-- <!-- <input type="text" placeholder="Search topic here" name=""> --> --}}
                                <input type="text" name="forum_search" id="forum_search" placeholder="Search forum here"
                                    name="">
                                <!-- <input type="text" id="name" name="name" class="form-control"> -->
                                <div class="list-group" id="forumList">
                                </div>
                            </div>
                        </div>
                        @if (Auth::check())
                            <div class="uploadssong-btn addtopic-btn">
                                <a href="" class="fill-btn plusbtn"
                                    data-id="{{ $content['forumDetails']->forumDetailsData->id }}" data-toggle="modal"
                                    data-target="#newTopicModal">
                                    <img src="{{ url('public/assets/frontend/img/btnplus.svg') }}" alt="plusbtn">
                                    Add Comment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>


                <div class="forum-listing-content">
                    <div class="forum-iteams-main forum-detail-main">
                        <div class="forum-list-iteam">
                            <div class="forum-likeshits">
                                @if (!Auth::check())
                                    <img src={{ url('public/assets/frontend/img/heart.png') }} width="" height="" alt="" />
                                @else

                                    <label class="heart heart-big">
                                        <input type="checkbox" value="yes"
                                            data-id="{{ $content['forumDetails']->forumDetailsData->id }}"
                                            class="forumLikedDisliked" name="heart"
                                            {{ $content['forumDetails']->forumDetailsData->liked == 1 ? 'checked' : '' }}>
                                        <span class="heart-checkmark"></span>
                                    </label>
                                @endif
                                <span id="checkvalue">{{ $content['forumDetails']->forumDetailsData->noOfLikes }}</span>

                            </div>
                            <div class="forum-list-content">
                                <p class="s1">{{ $content['forumDetails']->forumDetailsData->title }}</p>
                                <div class="forums-authorsmain">
                                    <div class="forumauthor-details">
                                        <img src="{{ $content['forumDetails']->forumDetailsData->image }}" />
                                        <span
                                            class="forumauth-titles">{{ $content['forumDetails']->forumDetailsData->userName }}</span>
                                        <span
                                            class="forumauth-time">{{getFormatedDateForWeb($content['forumDetails']->forumDetailsData->created_at)}}</span>
                                    </div>
                                    <div class="forums-detail-btn">
                                        <img src="{{ url('public/assets/frontend/img/forum-msg.svg') }}" width="" height=""
                                            alt="" />
                                        <span>{{ $content['forumDetails']->forumDetailsData->noOfComents }}</span>
                                    </div>
                                </div>
                                <span>{!! nl2br(e($content['forumDetails']->forumDetailsData->desc)) !!}</span>
                                {{-- <span>{{$content['forumDetails']->forumDetailsData->desc}}</span> --}}
                                <div class="forum-anscomments">
                                    <p class="s2">{{$content['forumDetails']->forumDetailsData->noOfComents}} Answers</p>
                                    @foreach ($content['forumComments']->forumCommentData->list as $key => $value)
                                        <div class="forum-coments-initem">
                                            <div class="forum-likeshits">
                                                @if (!Auth::check())
                                                    <img src={{ url('public/assets/frontend/img/heart.png') }} width=""
                                                        height="" alt="" />
                                                @else
                                                    <label class="heart heart-big">
                                                        <input type="checkbox" value="yes"
                                                            data-id="{{ $value->forumCommentId }}"
                                                            class="forumCommentLikedDisliked" name="heart"
                                                            {{ $value->liked == 1 ? 'checked' : '' }}>
                                                        <span class="heart-checkmark"></span>
                                                    </label>
                                                @endif
                                                <span id="checkCommentValue">{{ $value->noLikes }}</span>
                                            </div>

                                            <div class="forum-coment-desc">
                                                {{-- <span>{{$value->comment}} </span> --}}
                                                <span>{!! nl2br(e($value->comment)) !!} </span>
                                                <div class="forumauthor-details">
                                                    <img src="{{ $value->image }}" />
                                                    <span class="forumauth-titles">{{ $value->createdBy }}</span>
                                                    <span class="forumauth-dot"></span>
                                                    <span
                                                        class="forumauth-time">{{getFormatedDateForWeb($value->createdAt)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="forumlist-loadmore">
                                    <input type="hidden" name="page_no" id="page_no"
                                        value="{{ $content['forumComments']->pageNo }}">
                                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id_val" id="id_val"
                                        value="{{ $content['forumDetails']->forumDetailsData->id }}">
                                    <button class="border-btn clickLoadMore">Load More</button>
                                </div>
                            </div>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Comment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><img
                                    src="{{ url('public/assets/frontend/img/cancel-popup.svg') }}" /></span>
                        </button>
                    </div>
                    <form id="forum-comment-add" class="modal-body" method="POST"
                        action="{{ route('forumCommentCreate') }}">
                        @csrf
                        <input type="hidden" name="forum_id" value="{{ $content['forumDetails']->forumDetailsData->id }}">
                        <div class="inputs-group">
                            <textarea name="comment" id="comment"></textarea>
                            <span>Comment*</span>
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
            var id_val = $('#id_val').val();
            let new_page = parseInt(page) + 1;
            loadMoreContent(new_page, id_val);
        })

        function loadMoreContent(page, id_val) {
            $.ajax({
                url: "{{ route('forumLoadMoreComments') }}",
                method: "POST",
                data: {
                    'page': page,
                    'id_val': id_val,
                    "_token": $('#token').val(),
                },
                success: function(response) {

                    if (response) {
                        $('.forum-anscomments').append(response);
                        $('#page_no').val(page);
                        $('.loader-bg').addClass('d-none');
                    } else {
                        $('.clickLoadMore').hide();
                        $('.loader-bg').addClass('d-none');
                    }
                }
            })
        }

        $('#forum_search').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('autocomplete.fetch') }}",
                    method: "POST",
                    data: {
                        query: query,
                        _token: _token
                    },
                    success: function(data) {
                        $('#forumList').fadeIn();
                        $('#forumList').html(data);
                    }
                });
            }
        });

        $(document).on('click', 'li a', function() {
            console.log($(this).prop('disabled'))
            if (!$(this).hasClass('disabled')) {
                $('#forum_search').val($(this).text());
            }
            $('#forumList').fadeOut();
        });

        $("#forum-comment-add").validate({
            ignore: [],
            rules: {
                comment: "required",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });


        $(document).on('change', '.forumLikedDisliked', function() {
            // artistLikeDislike
            var forumId = $(this).data('id');
            var thisis = $(this);

            if (forumId) {
                $.ajax({
                    url: "{{ route('forumLikeDislike') }}",
                    method: 'post',
                    data: 'f_id=' + forumId + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        console.log(response);
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        thisis.closest('.forum-likeshits').find('#checkvalue').text(response.component
                            .no_likes)

                    }
                })
            }
        });


        $(document).on('change', '.forumCommentLikedDisliked', function() {
            // artistCommentLikeDislike
            var forumCommentId = $(this).data('id');
            var thisis = $(this);

            if (forumCommentId) {
                $.ajax({
                    url: "{{ route('forumCommentLikeDislike') }}",
                    method: 'post',
                    data: 'f_id=' + forumCommentId + '&_token={{ csrf_token() }}',
                    success: function(response) {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        thisis.closest('.forum-likeshits').find('#checkCommentValue').text(response
                            .component.no_likes)
                    }
                })
            }
        });
    </script>
@endsection
