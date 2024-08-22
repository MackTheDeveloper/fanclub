@extends('frontend.layouts.master')
@section('title',$cms->name)
@section('metaTitle',$cms->seo_title)
@section('metaKeywords',$cms->seo_meta_keyword)
@section('metaDescription',$cms->seo_description)
@section('content')
<!--------------------------
        TERMS CONDITIONS START
--------------------------->

<div class="terms-condition">
  <div class="container">
    @if(empty($mobile))
    <div class="breadCrums">
      <ul>
        <li><a href="{{url('/')}}">fanclub</a></li>
        <li>Terms & Conditions</li>
      </ul>
    </div>
    @endif
    {!! $cms->content !!}
  </div>
</div>


<!--------------------------
        TERMS CONDITIONS END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
</script>
@endsection