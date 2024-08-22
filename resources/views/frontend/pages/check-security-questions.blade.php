@extends('frontend.layouts.master')
@section('title','Security Question')
{{--@section('metaTitle',$cms->seo_title)--}}
{{--@section('metaKeywords',$cms->seo_meta_keyword)--}}
{{--@section('metaDescription',$cms->seo_description)--}}
@section('content')
<!--------------------------
        CONTACT US START
--------------------------->

<div class="contact-us">
    <h4>Recover your email address</h4>
    <p>Answer these security questions to recover your account information.</p>
    @php($showQuestions = true)
    @if(Session::has('data'))
      @php($data = Session::get('data'))
      @if(isset($data)&& $data->statusCode=='200')
        <p>Your account email address is : {{$data->component->email}}</p>
        <a class="fill-btn" href="{{route('showForgotPassword')}}">Go to Reset Password</a>
        @php($showQuestions = false)
      @else
        <label class="error">{{$data->component ?? ''}}</label>
      @endif
    @endif
    @if($showQuestions)
    <form method="POST" action="{{url('security-question-check')}}" id="securityQuestionCheckForm">
        @csrf
        <div>
          @foreach($content->SecurityQuestions as $question)
            <input type="hidden" name="question[]" value="{{$question->key}}">
            <div class="inputs-group">
                <input type="text" name="answer_{{$question->key}}" value="" class="answers">
                <span>{{$question->value}}</span>
            </div>
            @endforeach
        </div>
        <button class="fill-btn">Submit</button>
    </form>
    @endif
</div>

<!--------------------------
        CONTACT US END
--------------------------->
@endsection
@section('footscript')
<script>
  // $('#securityQuestionCheckForm').validate();
  // $('.answers').each(function() {
  //     $(this).rules("add", {
  //         required: function(element){
  //             var length = 0;
  //             $('.answers').each({
                
  //             })
  //         },
  //         messages: {
  //           required: "optional custom message"
  //         }
  //     });
  // });
</script>
@endsection
