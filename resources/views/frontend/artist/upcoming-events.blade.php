@section('title','Upcoming Events')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        UPCOMING EVENTS START
--------------------------->
<div class="upcoming-event add-gradient-only">
    <div class="header-gradient">
        <div class="container">
            <div class="header-content">
                <div class="breadCrums">
                    <ul>
                        <li><a href="{{ url('/') }}" >fanclub</a></li>
                        @if(Auth::check() && Auth::user()->id==$detail['id'])
                            <li><a href="{{ route('ArtistProfile') }}" >{{$detail['fullname']}}</a></li>
                        @else
                            <li><a href="{{ route('allArtists') }}" >Artists</a></li>
                            <li><a href="{{ route('artistDetail',$detail['slug']) }}" >{{$detail['fullname']}}</a></li>
                        @endif
                        
                        <li>Upcoming Events</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="event-data">
        <div class="container">
            <h5>Upcoming Events of {{$detail['fullname']}}</h5>
            <div class="row">
                @if(count($content->artistEventData))
                    @foreach($content->artistEventData as $key=>$row)
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                            <div class="event-box">
                                <img src="{{$row->banner}}" alt="" />
                                @if(Auth::check() && Auth::user()->id==$row->artistId)
                                <div class="date-box left-date-box">
                                    <input type="hidden" name="dateValue" value="{{$row->date}}">
                                    <p class="s1">{{date('d',strtotime($row->date))}}</p>
                                    <span>{{date('M',strtotime($row->date))}}</span>
                                </div>
                                <div class="dropdown c-dropdown round-drop-news">
                                    <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <img src="{{asset('public/assets/frontend/img/menu-dot.svg')}}" class="c-icon" />
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('artistEventEdit',$row->id) }}">
                                            <img src="{{asset('public/assets/frontend/img/edit.svg')}}" alt="" />
                                            <span>Edit</span>
                                        </a>
                                        <a class="dropdown-item deleteEvent" data-id="{{$row->id}}" href="javascript:void(0)">
                                            <img src="{{asset('public/assets/frontend/img/delete.svg')}}" alt="" />
                                            <span>Delete</span>
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="date-box">
                                    <input type="hidden" name="dateValue" value="{{$row->date}}">
                                    <p class="s1">{{date('d',strtotime($row->date))}}</p>
                                    <span>{{date('M',strtotime($row->date))}}</span>
                                </div>
                                @endif
                                <div class="title-content">
                                    <p class="s2">{{$row->name}}</p>
                                    <span class="t-content">{{$row->description}}</span>
                                    <div class="time-location">
                                        <a class="location" href="{{$row->location_url?:'javascript:void(0)'}}" {{$row->location_url?'target="_blank"':''}}>
                                            <img src="{{asset('public/assets/frontend/img/location.svg')}}" alt="" />
                                            {{$row->location}}
                                        </a>
                                        <div class="time">
                                            <img src="{{asset('public/assets/frontend/img/time.svg')}}" alt="" />
                                            {{$row->time}}
                                        </div>
                                        <a href="javascript:void(0)" class="a showMoreEvent" data-toggle="modal" data-target="#upcomingEventModal">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <p>No Events are there.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Popup -->
<div class="modal fade deletePopup" id="deleteEventModal" tabindex="-1" role="dialog"
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
                    <p class="blur-color">Are you sure you want to delete the record? <br> This process can't be undone</p>
                    <input type="hidden" name="eventId" id="eventId">

                    <div class="delete-footer">
                        <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="button" class="fill-btn deleteEventConfirm">Delete</button>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade newsPopup" id="upcomingEventModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{asset('public/assets/frontend/img/cancel-popup.svg')}}"></img></span>
                </button>
            </div>
            <div class="modal-body"><p></p></div>
            <div class="modal-footer text-left">
                <div class="time-location-popup">
                    <a class="location" href="javascript:void(0)">
                        <img src="{{ asset('public/assets/frontend/img/location-black.svg') }}" alt="" />
                        <span>Unknown</span>
                    </a>
                    <div class="time">
                        <img src="{{ asset('public/assets/frontend/img/time-black.svg') }}" alt="" />
                        <span>Undefined</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--------------------------
        UPCOMING EVENTS END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
    $(document).on('click','.showMoreEvent',function(){
        var content = $(this).closest('.event-box').find('.t-content').text();
        var date = $(this).closest('.event-box').find('.date-box input').val();
        var time = $(this).closest('.event-box').find('.time-location .time').text().trim();
        var location = $(this).closest('.event-box').find('.time-location .location').text().trim();
        var location_url = $(this).closest('.event-box').find('.time-location .location').attr('href');
        var title = $(this).closest('.event-box').find('.title-content .s2').text();
        $('#upcomingEventModal .modal-body p').html(nl2br(content));
        $('#upcomingEventModal .modal-footer .location span').text(location);
        if (location_url!='javascript:void(0)') {
            $('#upcomingEventModal .modal-footer .location').attr('href',location_url).attr('target','_blank');
        }
        $('#upcomingEventModal .modal-footer .time span').text(date+' '+time);
        $('#upcomingEventModal .modal-header h5').text(title);
    });
    $(document).on('click','.deleteEvent',function(){
        var eventId = $(this).data('id');
        // eventId
        $('#deleteEventModal #eventId').val(eventId);
        $('#deleteEventModal').modal('show');
    })

    $(document).on('click','.deleteEventConfirm',function(){
        // $('#exampleModalCenter').modal('hide')
        $('.loader-bg').removeClass('d-none');
        var eventId = $('#deleteEventModal #eventId').val();;
        var token = "{{ csrf_token() }}";
        if(eventId){
            $.ajax({
                url:"{{ route('artistEventDelete') }}",
                method:'post',
                data:{id:eventId,_token:token},
                success:function(response){
                    if (response.statusCode=='200') {
                        $('#deleteEventModal').modal('hide');
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        setTimeout(function(){ window.location.reload(); }, 1000);
                        $('.loader-bg').addClass('d-none');
                    }
                }
            });
        }else{

        }
    });
</script>
@endsection