@foreach($content['myReviewList']->myReviewListData->list as $key=>$row)
                        <div class="reviews-iteams-main">
                            <div class="reviews-iteam">
                                <div class="review-featured-head">
                                @if($row->type == 'song')
                                <img src="{{url(str_replace("/opt/lampp/htdocs/clubfan/", "",$row->songIcon))}}" />
                                @elseif($row->type == 'artist')
                                <img src="{{ App\Models\UserProfilePhoto::getProfilePhoto($row->artistId) }}" />
                                 @endif
                                <div class="review-featured-title">
                                @if($row->type == 'song')
                                <h5>{{$row->songName}}</h5>
                                @elseif($row->type == 'artist')
                                <h5>{{$row->artistName}}</h5>
                                @endif      
                                </div>
                                </div>
                                {{-- <div class="review-dottedmenu"></div> <!-- Dotted Menu --> --}}
                                <div class="dropdown c-dropdown edit-review-dropdown">
                                    <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <img src="{{url('public/assets/img/menu-dot.svg')}}" class="c-icon" />
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" id="editModal" href="{{ url('my-reviews/review-edit/'.$row->reviewId) }}" data-id="{{$row->reviewId}}" data-toggle="modal" data-target="#editReviewPopup">
                                            <img src="{{url('public/assets/img/edit.svg')}}" alt="" />
                                            <span>Edit</span>
                                        </a>
                                      
                                        <a class="dropdown-item deleteReview" data-id="{{$row->reviewId}}" href="{{ url('my-reviews/review-delete/'.$row->reviewId) }}" >
                                            <img src="{{url('public/assets/img/delete.svg')}}" alt="" />
                                            <span>Delete</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                                    <span>{{$row->createdAt}}</span>
                                </div>
                                <span>{{$row->reviews}}</span>
                            </div>
                        </div>
                    @endforeach