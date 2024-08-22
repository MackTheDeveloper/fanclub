@extends('frontend.layouts.master')
@section('title',$cms->name)
@section('metaTitle',$cms->seo_title)
@section('metaKeywords',$cms->seo_meta_keyword)
@section('metaDescription',$cms->seo_description)
@section('content')
<!--------------------------
        ABOUT US START
--------------------------->

<div class="about-us">
    @if(empty($mobile))
    <div class="container">
        <div class="breadCrums">
            <ul>
                <li><a href="{{url('/')}}">fanclub</a></li>
                <li>About fanclub</li>
            </ul>
        </div>
    </div>
    @endif
    <div class="container">
        <div class="about-banner">
            <img src="{{asset('public/assets/frontend/img/about-banner.png')}}" alt="" />
        </div>
    </div>
    {!! $cms->content !!}

</div>

<!--------------------------
        ABOUT US END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
</script>
@endsection
