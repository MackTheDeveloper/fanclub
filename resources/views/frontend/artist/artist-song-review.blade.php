@section('title','Reviews')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        ARTIST SONG REVIEW START
--------------------------->
<div class="myreview-songspage">
    <div class="container">
        <!-- breadcrumb Component is here    -->
        <div class="breadcrumb-section">
            <div class="breadCrums">
                <ul>
                    <li><a href="{{url('/')}}">fanclub</a></li>
                    <li>Reviews</li>
                </ul>
            </div>
        </div>

        <h5>Reviews of My Songs</h5>
        <div class="reviewmysong-row">
            <div class="thumb-col">
                <div class="myreview-songcard">
                    <img src="{{$content->songIcon}}" style="height: 200px;width:200px" alt="Song Thumbnail" />
                    <p class="s1">{{$content->songName}}</p>
                    <span class="caption">{{$content->artistName}}</span>
                </div>
            </div>
            <div class="review-col">
                <div class="mysongs-review-content">
                    @if(!empty($content->myReviewListData->list))
                    <div class="append-items">
                    @foreach($content->myReviewListData->list as $key=>$row)
                    <div class="mysongs-review-item">
                        <div class="songreviews-ratingbar">
                            <div class="reviews-data">
                                <div class='rating-stars'>
                                    <div class="show-star">
                                        @for ($i = 1; $i <= 5; $i++) @if($i <=$row->ratings)
                                            <div class="fill-star"></div>
                                            @else
                                            <div class="blank-star"></div>
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="review-dottedmenu"></div> -->
                            <div class="dropdown c-dropdown">
                                <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                    <img src="{{asset('public/assets/frontend/img/menu-dot.svg')}}"  class="c-icon" />
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item reject-review" data-id="{{$row->reviewId}}" href="javascript:void(0);" >
                                        <img src="{{url('public/assets/frontend/img/d-img2.png')}}" alt="" />
                                        <span>Reject</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="mysong-reviwtxt toggle-parent">
                            <p class="blur-color toggle-content toggle-apply-2">{{$row->reviews}}</p>
                            <a href="javascript:void(0)" class="a toggle-btns">Read More</a>
                        </div>
                        <div class="forums-authorsmain">
                            <div class="forumauthor-details">
                                <img style="width: 32px;height: 32px;border-radius: 50%;" src="{{$row->image}}" />
                                <span class="forumauth-titles">{{$row->userName}}</span>
                                <span class="forumauth-time">{{$row->createdAtForWeb}}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                  </div>
                    @else
                    <h5>No Reviews found..</h5>
                    @endif
                </div>
                <div class="forumlist-loadmore text-center">
                    <input type="hidden" name="SongId" id="SongId" value="{{($content->songID)}}">
                    <input type="hidden" name="page_no" id="page_no" value="{{($content->pageNo)}}">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <button class="border-btn clickLoadMore">Load More</button>
                </div>
            </div>
        </div>
    </div>
</div>


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
            <p class="blur-color">Are you sure you want to reject this review ? <br> This process can't be undone</p>
            <input type="hidden" name="reviewId" id="reviewId">

            <div class="delete-footer">
                <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="button" class="fill-btn deleteReviewConfirm">Ok</button>
            </div>
        </div>


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

$(document).on('click','.reject-review',function(){
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
                url:"{{ route('rejectReview') }}",
                method:'post',
                data:'reviewId='+reviewId+'&_token={{ csrf_token() }}',
                success:function(response){
                    if (response.statusCode=='200') {
                        $('.loader-bg').addClass('d-none');
                        $('#deleteNewsModal').modal('hide');
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        setTimeout(function(){ window.location.reload(); }, 500);
                    }
                }
            });
        }else{

        }
    });

// $(document).on('click','.reject-review',function(){
//     var reviewId = $(this).data('id');
//     $.ajax({
//         url:"{{ route('rejectReview') }}",
//         method:'post',
//         data:'reviewId='+reviewId+'&_token={{ csrf_token() }}',
//         success:function(response){
//             if(response.statusCode=='200'){
//               toastr.clear();
//     					toastr.options.closeButton = true;
//     					toastr.options.timeOut = 0;
//     					toastr.success(response.message);
//                         setTimeout(function(){
//                         window.location.reload(1);
//                         }, 1000);
//             }
//             else{
//               toastr.clear();
//     					toastr.options.closeButton = true;
//     					toastr.options.timeOut = 0;
//     					toastr.error(response.message);
//             }
//         }

//     })
// });
    $(document).on('click','.clickLoadMore',function () {
        $('.loader-bg').removeClass('d-none');
        var page = $('#page_no').val();
        var SongId = $('#SongId').val();
        let new_page = parseInt(page)+1;
        loadMoreContent(new_page,SongId);
    })
    function loadMoreContent(page,SongId)
    {
        $.ajax({
                url: "{{ route('myReviewLoadMore') }}",
                method: "POST",
                data:{
                  'page':page, "_token": $('#token').val(),'SongId': SongId,
                },

                success : function (response)
                {
                    if(response)
                    {
                        $('.append-items').append(response);
                        $('#page_no').val(page);
                        $('.loader-bg').addClass('d-none');
                    }
                    else
                    {
                        $('.clickLoadMore').hide();
                        $('.loader-bg').addClass('d-none');
                    }
                }
            }
        )
    }
</script>
@endsection
