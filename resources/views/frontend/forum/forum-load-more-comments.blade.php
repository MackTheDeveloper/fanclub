@foreach($content['forumComments']->forumCommentData->list as $key => $value)
<div class="forum-coments-initem">
    <div class="forum-likeshits">
        @if(!Auth::check())
        <img src={{url('public/assets/frontend/img/heart.png')}} width="" height="" alt=""/>
    @else
    <label class="heart heart-big">
        <input type="checkbox" value="yes" data-id="{{$value->forumCommentId}}" class="forumCommentLikedDisliked" name="heart" {{($value->liked == 1) ? "checked" : "" }}>
        <span class="heart-checkmark"></span>
    </label>
    @endif
    <span id="checkCommentValue">{{$value->noLikes}}</span>
    </div>

    <div class="forum-coment-desc">
        {{-- <span>{{$value->comment}} </span> --}}
        <span>{!! nl2br(e($value->comment)) !!} </span>
        <div class="forumauthor-details">
            <img src="{{$value->image}}" />
            <span class="forumauth-titles">{{$value->createdBy}}</span>
            <span class="forumauth-dot"></span>
            <span class="forumauth-time">{{getFormatedDateForWeb($value->createdAt)}}</span>
        </div>
    </div>
</div>
@endforeach
