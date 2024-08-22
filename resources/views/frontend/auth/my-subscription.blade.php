@section('title', 'My Subscription')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
                            SIGN UP START
                    --------------------------->

<div class="subscription">
    <div class="sub-banner">
        <img src="{{ asset('public/assets/frontend/img/g-banner.png') }}" alt="" />
    </div>
    <div class="subscripation-content">
        <h4>Subscription</h4>
        <div class="sub-table">
            <table>
                <tr>
                    <td>Benefits</td>
                    <!-- <td></td> -->
                    <td></td>
                </tr>
                @foreach ($content['benefits']->benefitsData as $item)
                <tr>
                    <td>{{$item->title}}</td>
                    <!-- <td><img src="{{ asset('public/assets/frontend/img/red-sign.svg') }}" alt="" /></td> -->
                    <td><img src="{{ asset('public/assets/frontend/img/red-sign.svg') }}" alt="" /></td>
                </tr>
                @endforeach
            </table>
        </div>
        @if ($content['mySubscription']->mySubscriptionData)
        <div class="subscripation-detail">
            <div class="sub-items">
                <div class="sub-item-header">
                    <p class="s1">Current Subscription</p>
                    <p class="s1 red-color"></p>
                </div>
                <span>Subscription valid untill: <span class="red-color">
                        {{ $content['mySubscription']->mySubscriptionData->subscriptionEndDate }}</span>
            </div>
            <div class="sub-items">
                <div class="sub-item-header">
                    <p class="s1">
                        {{ $content['mySubscription']->mySubscriptionData->currentSubscriptionType == 1 ? 'Monthly' : 'Yearly' }}
                    </p>
                    <p class="s1 red-color"> {{ $content['mySubscription']->mySubscriptionData->subscriptionName }}</p>
                </div>
                <!-- <span>Es un hecho establecido hace.</span> -->
            </div>
        </div>

        {{-- @if ($content['hasYearlySubscription']->isActive == 1 && $content['mySubscription']->mySubscriptionData->currentSubscriptionType == 1) --}}
        @if ($content['hasYearlySubscription']->isActive == 1)
        <div class="subscripation-detail">
            <div class="sub-items">
                <div class="sub-item-header">
                    <p class="s1">{{$content['hasYearlySubscription']->title}}</p>
                    <p class="s1 red-color"></p>
                </div>
                <span>Start Date: <span class="red-color">
                        {{ $content['hasYearlySubscription']->hasYearlySubscriptionData->yearlySubscriptionStartDate }}</span></br>
                <span>Subscription valid untill: <span class="red-color">
                        {{ $content['hasYearlySubscription']->hasYearlySubscriptionData->yearlySubscriptionEndDate }}</span>
            </div>
            <div class="sub-items">
                <div class="sub-item-header">
                    <p class="s1">
                        Yearly
                    </p>
                    <p class="s1 red-color"> {{ $content['hasYearlySubscription']->hasYearlySubscriptionData->subscriptionName }}</p>
                </div>
                <!-- <span>Es un hecho establecido hace.</span> -->
            </div>
        </div>
        @elseif ($content['mySubscription']->mySubscriptionData->currentSubscriptionType == 1)
        <div class="like-plan">
            <p class="s1">{{$content['annualSubscription']->description}}</p>
            <div class="like-plan-box">
                <div class="left-column">
                    <p class="s1">{{$content['annualSubscription']->title}}</p>
                    <span>{{ $content['annualSubscription']->annualSubscriptionData->description }}</span>
                </div>
                <div class="right-column">
                    <p>{{ $content['annualSubscription']->annualSubscriptionData->price }}</p>
                    <span>Per Year</span>
                </div>
            </div>
        </div>
        <a href="{{ url('upgrade-subscription') }}" class="fill-btn">Upgrade Now</a>
        @endif
        <a href="{{ url('cancel-subscription') }}" class="fill-btn">Cancel Subscription</a>
        <!-- <button class="fill-btn">Upgrade Now</button> -->
        @else
        <h6>You are not subscribe</h6>
        @endif
    </div>
</div>

<!--------------------------
                            SIGN UP END
                    --------------------------->
@endsection
@section('footscript')
@endsection