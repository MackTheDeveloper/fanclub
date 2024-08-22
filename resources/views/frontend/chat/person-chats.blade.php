@if(count($content))
    @foreach($content as $key=>$row)
        @if($key>0 && $row->createdDate!=$content[$key-1]->createdDate)
            <div class="day-status" id="{{date('Ymd',strtotime($row->createdAt))}}">
                <p class="caption">{{getDayforChatState($row->createdDate)}}</p>
            </div>  
        @elseif($key==0)
            <div class="day-status" id="{{date('Ymd',strtotime($row->createdAt))}}">
                <p class="caption">{{getDayforChatState($row->createdDate)}}</p>
            </div>  
        @endif
        @if($row->senderId==Auth::user()->id)
            <div class="send-msg-box msgBox" data-chat="{{$row->id}}" data-chat-last="{{$row->lastId}}">
                <div class="send-msg">
                    @foreach($row->message as $key1=>$row1)
                        <div class="send-msg-content">
                            <span>{{$row1}}</span>
                        </div>
                    @endforeach
                    <p class="caption">{{$row->viewCreatedAt}}</p>
                </div>
            </div>
        @else
            <div class="receive-msg-box msgBox" data-chat="{{$row->id}}" data-chat-last="{{$row->lastId}}">
                <div class="receive-msg">
                    @foreach($row->message as $key1=>$row1)
                        <div class="receive-msg-content">
                            <span>{{$row1}}</span>
                        </div>
                    @endforeach
                    <p class="caption">{{$row->viewCreatedAt}}</p>
                </div>
                <div class="receiver-img">
                    <img alt="" src="{{$row->senderIcon}}"/>
                </div>
            </div>
        @endif
    @endforeach
@endif