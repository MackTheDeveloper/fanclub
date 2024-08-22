@extends('admin.layouts.master')
@section('title','Reset Password')
@section('content')
    <!-- <style type="text/css">
        .bgimg {
            background-image:url({{url('public/admin/images/bg_image.jpg')}})
        }
    </style> -->
<div class="app-container app-theme-white body-tabs-shadow greenTheme">
    <div class="bgimg app-container form-pages reset-page">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="modal-dialog w-100 mx-auto">
                        <div class="modal-content">
                            <form id="loginForm" method="POST" action="{{ url(config('app.adminPrefix').'/reset-password') }}">
                                @csrf
                                <input name="reset_pass_email" type="hidden" value="{{$email}}">
                                <div class="modal-body">
                                    <div class="mx-auto text-center mb-3 fan-logo">
                                        <img src="{{url('public/images/Logo.svg')}}">
                                    </div>
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div class="welcome-text">Reset Password</div>
                                        </h4>
                                    </div>
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
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group inputs-group">
                                                <input name="password" id="password" type="password" class="input" value="">
                                                <span>Password</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group inputs-group">
                                                <input name="confirm_password" id="confirm_password" type="password" class="input" value="">
                                                <span>Confirm Password</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="divider"></div> -->
                                    <!-- <h6 class="mb-0">No account? <a href="javascript:void(0);" class="text-primary">Sign up now</a></h6> -->
                                </div>
                                <div class="modal-footer clearfix">
                                    {{-- <div class="float-left"><a href="{{url(config('app.adminPrefix').'/forgot-password')}}" class="btn-lg btn btn-link">Forgot Password</a></div> --}}
                                    
                                        <!-- <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Reset"> -->
                                        <button class="fill-btn" type="submit">Reset</button>
                                   
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
