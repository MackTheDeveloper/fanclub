@foreach($content['myReviewList']->myReviewListData->list as $key=>$row)
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
                <a class="dropdown-item" id="editModal" href="{{route('fanReviewEdit',$row->reviewId)}}" data-id="{{$row->reviewId}}" data-toggle="modal" data-target="#editReviewPopup">
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
        <span class="toggle-content toggle-apply-2">{{$row->reviews}}</span>
        <a href="javascript:void(0)" class="a toggle-btns">Read More</a>
    </div>
</div>
@endforeach
