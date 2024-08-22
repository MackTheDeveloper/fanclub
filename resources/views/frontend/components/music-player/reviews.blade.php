    <div class="review-list">
        <div class="review-list-header">
            <h6>Reviews</h6>
            <img src="{{ asset('public/assets/frontend/img/close.svg') }}" class="close-review-btn">
        </div>
        <div class="review-list-wrapper">
            <div class="review-and-addreview">
                <div class="big-review-box">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= $playerSong->playerSongData->generalReviews->averageRating)
                            <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                        @else
                            <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}" class="unfill-star"
                                alt="">
                        @endif
                    @endfor
                    <span>{{ (int) $playerSong->playerSongData->generalReviews->averageRating }}&nbsp;({{ $playerSong->playerSongData->generalReviews->totalReviews }}
                        Reviews)</span>
                </div>


                @include('frontend.components.reviews.action-add-review',['menus' =>
                ['popupAddReview'],'songId' => $playerSong->playerSongData->data->songId,'artistId' =>
                $playerSong->playerSongData->data->artistId])
            </div>
            <div class="video-review-scroll">
                @foreach ($songReviews->songReviewsData->list as $item)
                    <div class="video-reviews">
                        <div class="small-review-box">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $item->ratings)
                                    <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                @else
                                    <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                        class="unfill-star" alt="">
                                @endif
                            @endfor
                        </div>
                        <span class="blur-color toggle-current-ellips ellips-add">{{ $item->reviews }}</span>
                        <div class="client-date">
                            <p class="caption blur-color">— {{ $item->userName }}</p>
                            <p class="caption blur-color">{{ $item->createdAt }}</p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
