@php($title = (Auth::user()->role_id==3)?'Chat with artists':'Chat with your fans')
@section('title',$title)
@extends('frontend.layouts.master')
@section('content')
<!-- My Reviews Songs Page starts here -->
<div class="chat-page">
    <div class="chat-wrapper">
        <div class="chat-sidebar">
            <div class="search-bar">
                <div class="search-box">
                    <button><img alt="" src="{{asset('public/assets/frontend/img/search.svg')}}"/></button>
                    <input placeholder="Search" name="searchChat" class="searchChat" type="text"/>
                </div>
                <button>
                    <img alt="" class="toggleChats" src="{{asset('public/assets/frontend/img/up-down.svg')}}"/>
                    <input type="hidden" name="sortBy" value="1">
                </button>
            </div>
            <div class="chat-person-list">
            </div>
        </div>
        <div class="chat-board loaded-chat d-none">
            <div class="chat-board-header">
                <input type="hidden" name="chatWith" value="{{$artistId}}">
                <img alt="" class="back-chat" src="{{asset('public/assets/frontend/img/down-dark-arrow.svg')}}"/>
                <img alt="" class="setSrc d-none" src="{{asset('public/assets/frontend/img/artist5.png')}}"/>
                <h6 class="setName d-none">
                    Mitchel Hadid
                </h6>
                <a href="javascript:void(0)" class="refresh-icon d-none">
                    {{-- rotate-icon --}}
                    <img src="{{asset('public/assets/frontend/img/Refresh.svg')}}" alt="" />
                </a>
            </div>
            <div class="chat-board-data" id="chat-board-data">
            </div>
            <form class="chat-board-input send-hide-btn" id="sendMessageForm" method="POST" action="{{ route('intiateChat') }}">
                <input type="hidden" name="receiver_id" value="{{$artistId}}">
                <div class="emoji-input">
                    <button>
                        <img alt="" src="{{asset('public/assets/frontend/img/s6.svg')}}"/>
                        {{-- <img alt="" src="{{asset('public/assets/frontend/img/emoji.svg')}}"/> --}}
                    </button>
                    <input name="message" autocomplete="off" placeholder="Type a message..."/>
                </div>
                <button class="fill-btn send-btn sendMessage">
                    <img alt="" src="{{asset('public/assets/frontend/img/Subtract.svg')}}"/>
                </button>
            </form>
        </div>
        <div class="chat-board unloaded-chat">
            <div class="chat-default-screen">
                <img src="{{asset('public/assets/frontend/img/Chat-Illustration.png')}}" class="whiteImg" alt="" />
                <img src="{{asset('public/assets/frontend/img/Chat-Illustration-dark.png')}}" class="blackImg" alt="" />
                @if(Auth::user()->role_id==2)
                <h5>It’s nice to chat with fans</h5>
                <p class="blur-color">Choose a thread from your messages on the left and connect with your fans.</p>
                @else
                <h5>It’s nice to chat with Artists</h5>
                <p class="blur-color">Visit their page and drop them a message to connect.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade deletePopup" id="clearChatModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{asset('public/assets/frontend/img/modal-close.svg')}}" class="close" data-bs-dismiss="modal" aria-label="Close" />

                <div class="delete-photo">
                    <img src="{{asset('public/assets/frontend/img/delete-popup-img.svg')}}" alt="" class="web-view" />
                    <img src="{{asset('public/assets/frontend/img/delete-popup-img-mobile.svg')}}" alt="" class="mobile-view" />
                </div>
                <div class="delete-content">
                    <h5 class="modal-title">Hey, Wait!!</h5>
                    <p class="blur-color">Are you sure you want to delete this conversation? <br> It will only be deleted for you and no one else.</p>
                    <input type="hidden" name="personId" id="personId">

                    <div class="delete-footer">
                        <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="button" class="fill-btn clearChatConfirm">Delete</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection
@section('footscript')
<script src="{{ asset('public/assets/frontend/js/chat.js') }}?r=20220404" data-base-url="{{ url('/') }}"></script>
@endsection
