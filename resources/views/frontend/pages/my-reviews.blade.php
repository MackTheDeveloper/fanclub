@extends('frontend.layouts.master')
@section('title','My Reviews')
{{--@section('metaTitle',$cms->seo_title)--}}
{{--@section('metaKeywords',$cms->seo_meta_keyword)--}}
{{--@section('metaDescription',$cms->seo_description)--}}
@section('content')
    <!--------------------------
        My Reviews START
--------------------------->

    <!-- My Reviews Page starts here -->
    <div class="my-reviews-page">
        <div class="container">
            <div class="reviews-pagein">

                <!-- breadcrumb Component is here    -->
                <div class="breadcrumb-section">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li>My Reviews</li>
                        </ul>
                    </div>
                </div>

                <div class="valuable-reviews-section">
                    <div class="headof-sortbys">
                        <div class="sortbys-heading">
                            <h5>Your Valueable Reviews</h5>
                            <span>{{$total}} Reviews</span>
                        </div>
                        <div class="sortby-update">
                            <p>Sort By</p>
                            <select name="sort-by" id="sort-by" class="filterWeb">
                                <option value="latest">Last Updated</option>
                                <option value="old">Old</option>
                                <option value="three-month">Last 3 Months</option>
                                <option value="one-month">Last 1 Month</option>
                            </select>
                        </div>
                        <div class="filter-header">
                            <div class="d-flex sortIcons">
                                <img src="{{ asset('public/assets/frontend/img/sortbyicon.png') }}">
                                <span>Sort</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sort By mobile Menu start  (Open in mobile) -->

                    <div class="sortMenu">

                        <p class="s1">Sort By</p>
                        <img src="{{ asset('public/assets/frontend/img/close.svg') }}" class="closeIcons2 fixed-right" alt="close">

                        <div class="sortbar-navigation">
                            <ul>
                                <li><a class="filter-mob" data-filter="latest" href="javascript:void(0)">Last Updated</a></li>
                                <li><a class="filter-mob" data-filter="old" href="javascript:void(0)">Old</a></li>
                                <li><a class="filter-mob" data-filter="three-month" href="javascript:void(0)">Last 3 Months</a></li>
                                <li><a class="filter-mob" data-filter="one-month" href="javascript:void(0)">Last 1 Month</a></li>
                            </ul>
                        </div>
                    </div>


                    <!-- Repeat this div in a loop -->
                    <div class="append-items">
                        @foreach($content['myReviewList']->myReviewListData->list as $key=>$row)
                            {{-- artistName --}}
                            <div class="reviews-iteams-main">
                                <div class="reviews-iteam">
                                    <div class="review-featured-head">
                                        @if($row->type == 'song')
                                            <img src="{{$row->songIcon}}" />
                                            <div class="review-featured-title">
                                                <p class="s2">{{$row->songName}}</p>
                                                <p class="caption blur-color">{{$row->artistName}}</p>
                                            </div>
                                        @elseif($row->type == 'artist')
                                            <img src="{{ $row->artistImage }}" />
                                            <div class="review-featured-title">
                                                <p class="s2">{{$row->artistName}}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- <div class="review-dottedmenu"></div> Dotted Menu -->
                                    <div class="dropdown c-dropdown edit-review-dropdown">
                                        <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <img src="{{url('public/assets/img/menu-dot.svg')}}" class="c-icon" />
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" id="editModal" href="javascript:void(0)" data-id="{{$row->reviewId}}" >
                                                <img src="{{url('public/assets/img/edit.svg')}}" alt="" />
                                                <span>Edit</span>
                                            </a>

                                            <a class="dropdown-item deleteReview" data-id="{{$row->reviewId}}" href="javascript:void(0);" >
                                                <img src="{{url('public/assets/frontend/img/delete.svg')}}" alt="" />
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="reviews-data toggle-parent">
                                    <div class='rating-stars'>
                                        <div class="show-star">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if($i <=$row->ratings)
                                                    <div class="fill-star"></div>
                                                @else
                                                    <div class="blank-star"></div>
                                                @endif
                                            @endfor
                                        </div>
                                        <span>{{$row->createdAt}}</span>
                                    </div>
                                    <span class="blur-color toggle-content toggle-apply-2">{{$row->reviews}}</span>
                                    <a href="javascript:void(0)" class="a toggle-btns">Read More</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="review-loadmore text-center">
                        <input type="hidden" name="page_no" id="page_no" value="{{($content['myReviewList']->pageNo)}}">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <button class="border-btn clickLoadMore">Load More</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!--------------------------
            Edit Modal
    --------------------------->
    <div class="modal fade edit-review-popup" id="editReviewPopup" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Review</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="{{url('public/assets/frontend/img/cancel-popup.svg')}}"></img></span>
                    </button>
                </div>
                <form id="review-modal" class="modal-body">
                    <p class="s2" id="songName"></p>
                    <p class="caption blur-color" id="artistName"></p>
                    <div class="add-ratting-star" data-aos="fade-up">
                        <div class="starsDiv">
    						<ul id='stars'>
                                <li class='star red-star' title='Poor' data-value='1'></li>
    							<li class='star green-star' title='Fair' data-value='2'></li>
    							<li class='star green-star' title='Good' data-value='3'></li>
    							<li class='star yellow-star' title='Excellent' data-value='4'></li>
    							<li class='star yellow-star' title='WOW!!!' data-value='5'></li>
    						</ul>
                            <input id="star-value" class="hidden-input" />
                        </div>
                        <input id="idValue" class="hidden-input" />
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

					</div>
                    <label class="caption">Review</label>
                    <textarea id="comment"></textarea>
                    <div class="m-footer">
                        <button class="fill-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--------------------------
            Delete Modal
    --------------------------->
    <div class="modal fade addReviewPopup" id="deleteReviewModal1" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="{{asset('public/assets/frontend/img/cancel-popup.svg')}}" /></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure?</p>
                    <input type="hidden" name="reviewId" id="reviewId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="fill-btn deleteNewsConfirm">Okay</button>
                    <button type="button" class="fill-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!--------------------------
            My Reviews END
    --------------------------->
    <!--------------------------
            Delete Modal
    --------------------------->
    <div class="modal fade deletePopup" id="deleteReviewModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{asset('public/assets/frontend/img/modal-close.svg')}}" class="close" data-bs-dismiss="modal" aria-label="Close" />

                    <div class="delete-photo">
                        <img src="{{asset('public/assets/frontend/img/delete-popup-img.svg')}}" alt="" class="web-view" />
                        <img src="{{asset('public/assets/frontend/img/delete-popup-img-mobile.svg')}}" alt="" class="mobile-view" />
                    </div>
                    <div class="delete-content">
                        <h5 class="modal-title">Hey, Wait!!</h5>
                        <p class="blur-color">Are you sure you want to delete the record? <br> This process can't be undone</p>
                        <input type="hidden" name="reviewId" id="reviewId">

                        <div class="delete-footer">
                            <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" class="fill-btn deleteReviewConfirm">Delete</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!--------------------------
            My Reviews END
    --------------------------->
@endsection
@section('footscript')
    <script type="text/javascript">
    $(document).on('click','.deleteReview',function(){
        var reviewId = $(this).data('id');
        // eventId
        $('#deleteReviewModal #reviewId').val(reviewId);
        $('#deleteReviewModal').modal('show');
    });
    $(document).on('click','.deleteReviewConfirm',function(){
        // $('#exampleModalCenter').modal('hide')
        $('.loader-bg').removeClass('d-none');
        var reviewId = $('#deleteReviewModal #reviewId').val();;
        var token = "{{ csrf_token() }}";
        if(reviewId){
            $.ajax({
                url:"{{route('reviewDelete')}}",
                method:'POST',
                data:{id:reviewId,_token:token},
                success:function(response){
                    if (response.statusCode=='200') {
                        $('.loader-bg').addClass('d-none');
                        $('#deleteNewsModal').modal('hide');
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        setTimeout(function(){ window.location.reload(); }, 2000);
                    }
                }
            });
        }else{

        }
    });

    $(document).on('click','.clickLoadMore',function () {
            $('.loader-bg').removeClass('d-none');
            var page = $('#page_no').val();
            let new_page = parseInt(page)+1;
            ajaxSubmitData(new_page);
            // loadMoreContent(new_page);
        });

        $(document).on('change','select.filterWeb',function () {
            $('.loader-bg').removeClass('d-none');
            ajaxSubmitData(1,1);
            $('.clickLoadMore').show();
        });


        function ajaxSubmitData(page,html="0")
        {
            var filterData = $('select.filterWeb').val();
            $.ajax({
                url: "{{route('reviewLoadMore')}}",
                method: "POST",
                data:{
                    'page':page,'filter':filterData, "_token": $('#token').val(),
                },
                success : function (response)
                {
                    if(response)
                    {
                        $('.loader-bg').addClass('d-none');
                        if(html=="1"){
                            $('.append-items').html(response);
                        }else{
                            $('.append-items').append(response);
                        }
                        // $('.append-items').append(response);
                        $('#page_no').val(page);
                    }
                    else
                    {
                        $('.clickLoadMore').hide();
                        $('.loader-bg').addClass('d-none');
                    }

                    multiLineOverflows()
                }
            });
        }
        // $(document).on('click','.clickLoadMore',function () {
        //     var page = $('#page_no').val();
        //     let new_page = parseInt(page)+1;

        //     loadMoreContent(new_page);
        // })
        // function loadMoreContent(page)
        // {
        //     $.ajax({
        //             url: origin + '/clubfan/reviews-loadMore',
        //             method: "POST",
        //             data:{
        //               'page':page, "_token": $('#token').val(),
        //             },

        //             success : function (response)
        //             {
        //                 if(response)
        //                 {
        //                     $('.append-items').append(response);
        //                     $('#page_no').val(page);
        //                 }
        //                 else
        //                 {
        //                     $('.clickLoadMore').hide();
        //                 }
        //             }
        //         }
        //     )
        // }
        // $('select').on('change', function() {
        //     let data = this.value;
        //     var page = $('#page_no').val();

        //     $.ajax({
        //             url: origin + '/clubfan/sort-by',
        //             method: "POST",
        //             data:{
        //               'page':page, "_token": $('#token').val(),'data':data,
        //             },
        //             success : function (response)
        //             {
        //                 if(response)
        //                 {
        //                     $('.append-items').html(response);
        //                 }
        //             }
        //         }
        //     )
        // });


        $('#review-modal').on('submit',function(e){
            e.preventDefault();
            $('.loader-bg').removeClass('d-none');
            var stars = $('#star-value').val();
            var comments = $('#comment').val();
            var id = $('#idValue').val();
            $.ajax({
                url: "{{route('fanReviewUpdate')}}",
                method: "POST",
                data:{
                    'ratings':stars, 'comments':comments,"_token": $('#token').val(),'id':id
                },
                success:function(response){
                    $('#editReviewPopup').modal('hide')
                 // window.location.reload();
                    $('.loader-bg').addClass('d-none');
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.success(response.message);
                    setTimeout(function(){ window.location.reload(); }, 3000);
                },
            });
        });

        $(document).on('click','#editModal',function(){
        
        var reviewId = $(this).data('id');
        $.ajax({
            url:"{{ url('my-reviews/review-edit') }}/"+reviewId,
            success:function(response){
                if (response.statusCode=='200') {
                    $("#songName").empty().append(response.component.songName);
                    $("#artistName").empty().append(response.component.artistFName+' '+response.component.artistLName);
                    $("#comment").val(response.component.reviews);
                    $("#idValue").empty().val(response.component.id);
                    $("#star-value").val(response.component.ratings);
                    setStarbyVal($("#star-value"));
                    $('#editReviewPopup').modal('show')
                }
            }
        });
        });

        $(document).on('click','a.filter-mob',function(){
            var filter = $(this).data('filter');
            $('select.filterWeb').val(filter);
            ajaxSubmitData(1,1);
            $('.clickLoadMore').show();
            closeSortPopup()
        })
        $(document).ready(function(){
            multiLineOverflows();
        })
    </script>
@endsection
