@section('title', 'Artist News')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
                    ARTIST DETAIL START
            --------------------------->

    <div class="upcoming-event add-gradient-only">
        <div class="header-gradient">
            <div class="container">
                <div class="header-content">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            @if (Auth::check() && Auth::user()->id == $content['artistDetail']->artistDetailData->id)
                                <li><a
                                        href="{{ route('ArtistProfile') }}">{{ $content['artistDetail']->artistDetailData->name }}</a>
                                </li>
                            @else
                                <li><a href="{{ route('allArtists') }}">Artists</a></li>
                                <li><a
                                        href="{{ route('artistDetail', $content['artistDetail']->artistDetailData->slug) }}">{{ $content['artistDetail']->artistDetailData->name }}</a>
                                </li>
                            @endif
                            <li>News</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="event-data news pt-0">
            <div class="container">
                <!-- for only title use this -->
                <!-- <h5>News of Kaiya Stanton</h5> -->
                <!-- for only title use this -->

                <!-- button and title use this -->
                <div class="full-data-header">
                    <h5>News of {{ $content['artistDetail']->artistDetailData->name }}</h5>
                    {{-- <button class="fill-btn create-news-btn" data-toggle="modal" data-target="#addNewsModal">Create News</button> --}}
                </div>
                <!-- button and title use this -->
                <div class="row">
                    @foreach ($content['news']->newsData as $key => $row)
                        <div class="col-12  col-sm-12 col-md-6 col-lg-4">
                            <div class="news-box-border">
                                <div class="news-box">
                                    @if (Auth::check() && Auth::user()->id == $row->artistId)
                                        <div class="news-edit-sec">
                                            <h6 class='news-title-icon'>{{ $row->name }}</h6>
                                            <div class="dropdown c-dropdown edit-news-dropdown">
                                                <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                                    <img src="{{ asset('public/assets/frontend/img/menu-dot.svg') }}"
                                                        class="c-icon" />
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editNews" data-id="{{ $row->id }}" href="#"
                                                        data-toggle="modal" data-target="#editReviewPopup">
                                                        <img src="{{ asset('public/assets/frontend/img/edit.svg') }}"
                                                            alt="" />
                                                        <span>Edit</span>
                                                    </a>
                                                    <a class="dropdown-item deleteNews" data-id="{{ $row->id }}"
                                                        href="#">
                                                        <img src="{{ asset('public/assets/frontend/img/delete.svg') }}"
                                                            alt="" />
                                                        <span>Delete</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <h6 class='news-title'>{{ $row->name }}</h6>
                                    @endif
                                    <p class="blur-color">{{ $row->description }}</p>
                                    <a href="" class="a showMore" data-toggle="modal" data-target="#newsModal">Read
                                        More</a>
                                    <span class="date">{{ $row->date }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade newsPopup" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">About Me</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <span class="blur-color">20 Sep, 2021</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addNewsPopup" id="addNewsModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit News</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <form id="news-add" class="modal-body" method="POST" action="{{ route('artistNewsCreate') }}">
                    @csrf
                    <div class="inputs-group">
                        <input type="text" name="name" />
                        <span>News Title*</span>
                    </div>
                    <div class="inputs-group">
                        <textarea name="description"></textarea>
                        <span>Description*</span>
                    </div>

                    <div class="m-footer">
                        <button type="submit" class="fill-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Popup -->
    <div class="modal fade deletePopup" id="deleteNewsModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ asset('public/assets/frontend/img/modal-close.svg') }}" class="close"
                        data-bs-dismiss="modal" aria-label="Close" />

                    <div class="delete-photo">
                        <img src="{{ asset('public/assets/frontend/img/delete-popup-img.svg') }}" alt=""
                            class="web-view" />
                        <img src="{{ asset('public/assets/frontend/img/delete-popup-img-mobile.svg') }}" alt=""
                            class="mobile-view" />
                    </div>
                    <div class="delete-content">
                        <h5 class="modal-title">Hey, Wait!!</h5>
                        <p class="blur-color">Are you sure you want to delete the record? <br> This process can't be
                            undone</p>
                        <input type="hidden" name="newsId" id="newsId">

                        <div class="delete-footer">
                            <button type="button" class="border-btn" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="button" class="fill-btn deleteNewsConfirm">Delete</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!--------------------------
                    ARTIST DETAIL END
            --------------------------->
@endsection
@section('footscript')
    <script type="text/javascript">
        $("#news-add").validate({
            ignore: [],
            rules: {
                name: "required",
                description: "required",
            },
            messages: {
                name: "Please enter a title",
                description: "Please enter a description",
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

        $(document).on('click', '.showMore', function() {
            var content = $(this).parent().find('p.blur-color').text();
            var date = $(this).parent().find('span.date').text();
            var title = $(this).parent().find('h6').text();
            $('#newsModal .modal-body p').html(nl2br(content));
            $('#newsModal .modal-footer span.blur-color').text(date);
            $('#newsModal .modal-header h5').text(title);
        });
        $(document).ready(function() {
            $('.header-carousel .owl-carousel').owlCarousel({
                margin: 0,
                dots: true,
                nav: true,
                center: true,
                loop: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 3,
                        dots: false,
                    },
                    576: {
                        items: 3,
                        dots: false,
                    },
                    768: {
                        items: 3
                    },
                    1200: {
                        items: 3
                    }
                }
            })
        })
        // $(document).on('change keyup','.searchSong',function(){
        //     var value = $(this).val();
        //     var artistId = "{{ $content['artistDetail']->artistDetailData->id }}";
        //     $.ajax({
        //         url:"{{ route('filterSongs') }}",
        //         method:'post',
        //         data:'search='+value+'&filter[artist_id]='+artistId+'&_token={{ csrf_token() }}',
        //         success:function(response){
        //             $('.filteredSongList').html(response);
        //         }
        //     })
        // });

        $(document).on('click', '.editNews', function() {
            var newsId = $(this).data('id');
            $.ajax({
                url: "{{ url('artist/news/edit') }}/" + newsId,
                // method:'post',
                // data:{bio:bio,_token:token},
                success: function(response) {
                    if (response.statusCode == '200') {
                        $('#addNewsModal input[name="name"]').val(response.component.name).addClass(
                            'has-value');
                        $('#addNewsModal textarea[name="description"]').val(response.component
                            .description).addClass('has-value');
                        $('#addNewsModal form').attr('action', "{{ url('/artist/news/edit') }}/" +
                            response.component.id);
                        // $('.changeOnUpdateBio').html(bio);
                        $('#addNewsModal').modal('show');
                    }
                }
            });
        });

        $(document).on('click', '.deleteNews', function() {
            var newsId = $(this).data('id');
            // newsId
            $('#deleteNewsModal #newsId').val(newsId);
            $('#deleteNewsModal').modal('show');
        })

        $(document).on('click', '.deleteNewsConfirm', function() {
            // $('#exampleModalCenter').modal('hide')
            $('.loader-bg').removeClass('d-none');
            var newsId = $('#deleteNewsModal #newsId').val();;
            var token = "{{ csrf_token() }}";
            if (newsId) {
                $.ajax({
                    url: "{{ route('artistNewsDelete') }}",
                    method: 'post',
                    data: {
                        id: newsId,
                        _token: token
                    },
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('#deleteNewsModal').modal('hide');
                            $('.loader-bg').addClass('d-none');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 000);
                        }
                    }
                });
            } else {

            }
        });
    </script>
@endsection
