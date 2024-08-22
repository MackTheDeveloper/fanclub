@extends('frontend.layouts.master')
@section('title', 'Security Question')
{{-- @section('metaTitle', $cms->seo_title) --}}
{{-- @section('metaKeywords', $cms->seo_meta_keyword) --}}
{{-- @section('metaDescription', $cms->seo_description) --}}
@section('content')
    <!--------------------------
                    CONTACT US START
            --------------------------->

    <div class="contact-us">
        <h4>Security Questions </h4>
        <p>Answer five of these security questions in order to recover your account, should the need arise.</p>
        <form method="POST" action="{{ url('security-question') }}" id="securityQuestionForm">
            @csrf
            <div>
                @foreach ($content->SecurityQuestions->list as $question)
                    <input type="hidden" name="question[]" value="{{ $question->key }}">
                    <div class="inputs-group">
                        <input type="text" name="answer[{{ $question->key }}]" id="answer_{{ $question->key }}"
                            value="{{ $question->answer }}">
                        <span>{{ $question->value }}</span>
                        <!-- <label class="error">Error</label> -->
                    </div>
                @endforeach
            </div>
            <button type="submit" class="fill-btn">Submit</button>
        </form>
        <!-- <div class="follow-us">
                    <h5>Follow Us</h5>
                    <div class="contact-social">
                        <a href=""><img src="{{ asset('public/assets/frontend/img/fb-color.png') }}" alt="" /></a>
                        <a href=""><img src="{{ asset('public/assets/frontend/img/tw-color.png') }}" alt="" /></a>
                        <a href=""><img src="{{ asset('public/assets/frontend/img/insta-color.png') }}" alt="" /></a>
                        <a href=""><img src="{{ asset('public/assets/frontend/img/yt-color.png') }}" alt="" /></a>
                    </div>
                </div> -->
    </div>

    <!--------------------------
                    CONTACT US END
            --------------------------->
@endsection
@section('footscript')
    <script type="text/javascript">
        $("#securityQuestionForm").validate({
            ignore: [],
            rules: {},
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        //console.log(response); return false;
                        if (response.statusCode == '301') {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        } else {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.success(response.message);
                        }
                    }
                });
            }
        });

        
    </script>
@endsection
