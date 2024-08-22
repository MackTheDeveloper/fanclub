<div class="video-bottom-content">
    <h5>{{ $playerSong->playerSongData->data->songName }}</h5>
    <div class="default-star-box">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $playerSong->playerSongData->generalReviews->averageRating)
                <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
            @else
                <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}" class="unfill-star" alt="">
            @endif
        @endfor
        <span>{{ (int) $playerSong->playerSongData->generalReviews->averageRating }}&nbsp;({{ $playerSong->playerSongData->generalReviews->totalReviews }}
            Reviews)</span>
    </div>
</div>
<div class="video-artist-blog">
    <img src="{{ $playerSong->playerSongData->data->artistIcon }}" alt="" />
    <a href="{{ route('artistDetail',$playerSong->playerSongData->data->artistSlug) }}"><p class="s1">{{$playerSong->playerSongData->data->artistName }}</p></a>
</div>
