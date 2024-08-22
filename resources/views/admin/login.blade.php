@extends('admin.layouts.master')
@section('title','Login')
@section('content')
<!-- <style type="text/css">
    .bgimg {
        background-image:url({{url('public/admin/images/bg_image.jpg')}})
    }
    </style> -->

<div class="bgimg form-pages login-page">
    <div class="app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    
                    <div class="modal-dialog w-100 mx-auto">
                        <div class="modal-content">
                            <form id="loginForm" method="POST" action="{{ url(config('app.adminPrefix').'/login') }}">
                                @csrf
                                {!! app('captcha')->render(); !!}
                                <div class="modal-body">
                                    <div class="mx-auto text-center mb-3 fan-logo">
                                        <img src="{{url('public/images/Logo.svg')}}">
                                    </div>
                                    <div class="h5 modal-title text-center">
                                        <h4 class="mt-2">
                                            <div class="welcome-text">Welcome Back</div>
                                            <span class="span">Please sign in to your account below.</span>
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
                                    @if(Session::has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ Session::get('success') }}
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
                                                <input name="email" id="exampleEmail" type="text" class="input">
                                                <span>Email</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group inputs-group">
                                                <input name="password" class="input" id="examplePassword" type="password" class="form-control" value="{{Cookie::get('password')}}">
                                                <span>Password</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 forgot-link-div">
                                            <a href="{{url(config('app.adminPrefix').'/forgot-password')}}" class="link">Forgot Password</a>
                                        </div>
                                    </div>
                                    <div class="position-relative form-check">
                                        <!-- <input name="remember" id="remember" type="checkbox" {{(Cookie::get('remember') == 'checked') ? 'checked' : ''}} class="form-check-input"><label for="remember" class="form-check-label">Keep me logged in</label> -->
                                        <label class="ck">Keep me logged in
                                          <input type="checkbox" checked="checked" id="remember" {{(Cookie::get('remember') == 'checked') ? 'checked' : ''}} >
                                          <span class="ck-mark"></span>
                                        </label>
                                    </div>
                                    <!-- <div class="divider"></div> -->
                                    <!-- <h6 class="mb-0">No account? <a href="javascript:void(0);" class="text-primary">Sign up now</a></h6> -->
                                </div>
                                <div class="modal-footer clearfix">
                                    <button type="submit" class="fill-btn">Login to Dashboard</button>
                                        <!-- <input type="submit" name="submit" class="btn btn-secondary btn-lg" value="Login to Dashboard"> -->
                                </div>
                            </form>
                        </div>
                    </div>
                        <div class="text-center copy-text opacity-8 mt-3">Copyright © {{config('app.name_show')}} All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
