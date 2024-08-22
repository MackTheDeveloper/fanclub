@foreach ($content['forumList']->ForumListData->list as $key => $value)
    <div class="forum-list-iteam">
        <div class="forum-likeshits">
            @if (!Auth::check())
                <img src={{ url('public/assets/frontend/img/heart.png') }} width="" height="" alt="" />
            @else
                <label class="heart heart-big">
                    <input type="checkbox" value="yes" data-id="{{ $value->id }}" class="forumLikedDisliked"
                        name="heart" {{ $value->liked == 1 ? 'checked' : '' }}>
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
            {{-- <span> {!! nl2br(e($value->description)) !!}</span> --}}
            <p class="blur-color toggle-content toggle-apply-2"> {!! nl2br(e($value->description)) !!}</p>
            <a href="javascript:void(0)" class="a toggle-btns">Read More</a>
            <div class="forums-authorsmain">
                <div class="forumauthor-details">
                    <img src="{{ $value->createdByImage }}" />
                    <span class="forumauth-titles"> {{ $value->createdByName }}</span>
                    <span class="forumauth-time">{{ getFormatedDateForWeb($value->createdAt) }}</span>
                </div>
                <div class="forums-detail-btn">
                    <img src="{{ url('public/assets/frontend/img/forum-msg.svg') }}" width="" height="" alt="" />
                    <span>{{ $value->comments }}</span>
                </div>
            </div>
        </div>
    </div>
@endforeach
