@if(count($content))
    @foreach($content as $key=>$row)
        <div class="chat-person {{($selected==$row->chatWithId)?'active':''}}" data-other-id="{{$row->chatWithId}}" data-allow-message="{{$row->allowMessage}}">
            <img alt="" class="getSrc" src="{{$row->personIcon}}"/>
            @if($row->isUnread)
            <div class="msg-status-icon"></div>
            @endif
            <div class="chat-person-detail">
                <div class="name-date">
                    <p class="s2 getName">{{$row->chatWith}}</p>
                    <span class="caption blur-color">{{$row->viewCreatedAt}}</span>
                </div>
                <span>{{$row->message}}</span>
            </div>
            <div class="hover-delete-wrapper">
                <a href="javascript:void(0)" class="delete-chat"><img alt="" src="{{asset('public/assets/frontend/img/delete-white.svg')}}"/></a>
            </div>
        </div>
    @endforeach
@else
    <p class="s2 not-found-text">No {{(Auth::user()->role_id=="2")?"fans":"artists"}} found with your search criteria. </p>
@endif