@section('title','Sign In')
@extends('frontend.layouts.master')
@section('styles')
<link rel="stylesheet" href="{{asset('public/assets/frontend/css/jquery.ccpicker.css')}}">
@endsection
@section('content')
<!--------------------------
        SIGN UP START
--------------------------->
<div class="subscription-yer-no">
    <h4>Now Let's Get Started</h4>
    <p>Please give us some more info</p>
    <form id="subscriptionForm" method="POST" action="{{route('secondSignup')}}">
        @csrf
        <div class="introduce">
            <span>Did a fanclub artist introduce you?</span>
            <div class="radio-group">
                <label class="rd">Yes
                    <input type="radio" {{$content['artist']->selected?"disabled":""}} checked="checked" class="introduce-radio-btn" value="1" name="artist_introduce">
                    <span class="rd-checkmark"></span>
                </label>
                <label class="rd">No
                    <input type="radio" {{$content['artist']->selected?"disabled":""}} class="introduce-radio-btn" value="0" name="artist_introduce">
                    <span class="rd-checkmark"></span>
                </label>
            </div>
        </div>
        
        <div class="yes-select show">
            <div class="label-select">
                <span class="yesLabel">Select Artist</span>
                <span class="noLabel d-none">Discovered fanclub through</span>
                <select name="artist_id" {{$content['artist']->selected?"disabled":""}}>
                    <option value="">Select Artist</option>
                    @foreach($content['artist']->artistData as $key=>$row)
                        <option {{($content['artist']->selected==$row->id)?"selected":""}} value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @if ($content['artist']->selected)
            <input type="hidden" name="artist_introduce" value="1">
            <input type="hidden" name="artist_id" value="{{$content['artist']->selected}}">
        @endif
        {{-- <div class="no-select">
            <div class="label-select">
                <span>Discovered fanclub through</span>
                <select>
                    <option>Select...</option>
                </select>
            </div>
        </div> --}}

        <div class="choose-plan-wrapper">
            <h6>{{$content['subscription']->subscriptionData->title}}</h6>
            @foreach($content['subscription']->subscriptionData->list as $key=>$row)
            <label class="rd-plan">
                <input type="radio" value="{{$row->id}}" @if(!$key) checked="checked" @endif name="subscription_id">
                <span class="rd-plan-checkmark">
                    <div class="paln-data">
                        <div class="ck-value"></div>
                        <div class="plan-inner-data">
                            <p class="s1">{{$row->title}}</p>
                            <span>{{$row->description}}</span>
                            {{-- <span>Your monthly subscription is payable on the same date each month.</span> --}}
                        </div>
                    </div>
                </span>
            </label>
            @endforeach
            {{-- <label class="rd-plan">
                <input type="radio" name="plan">
                <span class="rd-plan-checkmark">
                    <div class="paln-data">
                        <div class="ck-value"></div>
                        <div class="plan-inner-data">
                            <p class="s1">$40.99 / Year</p>
                            <span>Upgrade your plan to an annual subscription and save money today!</span>
                        </div>
                    </div>
                </span>
            </label> --}}
        </div>
        {{-- <a class="fill-btn" href="payment.html">Continue</a> --}}
        <button class="fill-btn" type="submit">Continue</button>
    </form>
</div>

<!--------------------------
        SIGN UP END
--------------------------->
@endsection
@section('footscript')
<script src="{{asset('public/assets/frontend/js/jquery.ccpicker.js')}}"></script>
<script type="text/javascript">
    $(document).on('change','.introduce-radio-btn',function(){
        var value = $(this).val();
        if (value=="1") {
            $('.yesLabel').removeClass('d-none');
            $('.noLabel').addClass('d-none');
            $('.yes-select').addClass('show').find('select').prop('disabled',false);
        }else {
            $('.yesLabel').addClass('d-none');
            $('.noLabel').removeClass('d-none');
            $('.yes-select').removeClass('show').find('select').prop('disabled',true);
        }
    });

    $("#subscriptionForm").validate({
        ignore: [],
        rules: {
            artist_introduce: "required",
            artist_id: "required",
            subscription_id: "required",
        },
        messages: {
            artist_introduce: "artist introduction is required",
            artist_id: "Please select artist",
            subscription_id: "Please select subscription",
        },
        errorPlacement: function(error, element) {
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
    });
    
</script>
@endsection
