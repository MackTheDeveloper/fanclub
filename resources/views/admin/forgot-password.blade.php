@extends('admin.layouts.master')

@section('content')
<div class=" app-container app-theme-white body-tabs-shadow greenTheme">
    <div class="bgimg app-container form-pages forgot-page">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="modal-dialog w-100">
                        <div class="modal-content">
                            <form id="forgotPassForm" class="" method="POST" action="{{url(config('app.adminPrefix').'/forgot-password')}}">
                                @csrf
                                {!! app('captcha')->render(); !!}
                                @if(Session::has('success_msg'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ Session::get('success_msg') }}
                                    <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                                @if(Session::has('msg'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ Session::get('msg') }}
                                    <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                                @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" style="padding-bottom: 0px;" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div class="modal-body">
                                    <div class="mx-auto text-center mb-3 fan-logo">
                                        <img src="{{url('public/images/Logo.svg')}}">
                                    </div>
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div class="welcome-text">Forgot your Password?</div>
                                            <span class="span">Use the form below to recover it.</span>
                                        </h4>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="inputs-group">
                                                    <input name="forgot_email" id="forgot_email" type="text" class="input">
                                                    <span>Email</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="divider"></div> -->
                                    <!-- <h6 class="mb-0"><a href="javascript:void(0);" class="text-primary">Sign in existing account</a></h6></div> -->
                                </div>
                                <div class="modal-footer clearfix two-btns">
                                    <a href="{{url(config('app.adminPrefix').'/login')}}" class="border-btn">Back</a>
                                    <button class="fill-btn">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center copy-text opacity-8 mt-3">Copyright © {{ config('app.name_show')." ".date('Y') }}. All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('admin.include.bottom')
