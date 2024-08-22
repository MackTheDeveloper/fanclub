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
                <a class="dropdown-item reject-review" href="#" data-id="{{$row->reviewId}}">
                    <img src="{{asset('public/assets/frontend/img/d-img2.png')}}"  alt="" />
                    <span>Reject</span>
                </a>
            </div>
        </div>
    </div>
    {{-- <div class="mysong-reviwtxt">
        <p class="blur-color">{{$row->reviews}}</p>
    </div> --}}
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
