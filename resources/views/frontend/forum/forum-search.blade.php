@foreach($content['forumList']->ForumListData->list as $key => $value)
                            <div class="forum-list-iteam">
                                <div class="forum-likeshits">
                                    <img src={{url('public/assets/frontend/img/heart.png')}} width="" height="" alt=""/>
                                    <span>{{$value->likes}}</span>
                                </div>
                                <div class="forum-list-content">
                                    <a href="{{$url}}/{{$value->fId}}"><p class="s1">{{$value->topic}}</p></a>
                                    <span> {{$value->description}}</span>
                                    <div class="forums-authorsmain">
                                        <div class="forumauthor-details">
                                            <img src="{{\App\Models\UserProfilePhoto::getProfilePhoto($value->id)}}"/>
                                            <span class="forumauth-titles"> {{$value->createdBy}}</span>
                                            <span class="forumauth-dot"></span>
                                            <span class="forumauth-time"> {{getFormatedDateForWeb($value->createdAt)}}</span>
                                        </div>
                                        <div class="forums-detail-btn">
                                            <img src={{url('public/assets/frontend/img/forum-msg.svg')}} width="" height="" alt=""/>
                                            <span>{{$value->comments}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
