@extends('admin.layouts.master')
@section('title','Reset Password')
@section('content')
    <style type="text/css">
        .bgimg {
            background-image:url({{url('public/admin/images/bg_image.jpg')}})
        }
    </style>
<div class="app-container app-theme-white body-tabs-shadow greenTheme">
    <div class="bgimg app-container">
        <div class="h-100 bg-plum-plate bg-animation">
            <div class="d-flex h-100 justify-content-center align-items-center">
                <div class="mx-auto app-login-box col-md-8">
                    <div class="modal-dialog w-100 mx-auto">
                        <div class="modal-content">
                           <input name="reset_pass_email" type="hidden" value="{{$email}}">
                            <div class="modal-body">
                                <div class="h5 modal-title text-center">
                                    <h4 class="mt-2">
                                        <div>Congratulations!</div>
                                        <div>Your password has been changed successfully.</div>
                                    </h4>
                                </div>
                            </div>
                            <div class="modal-footer clearfix">
                                <a href="{{url(config('app.adminPrefix').'/login')}}">
                                    <button class="mb-2 mr-2 btn-icon btn btn-light"><i class="fa fa-arrow-circle-left btn-icon-wrapper"> </i>Go to login</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-white opacity-8 mt-3">Copyright © {{ config('app.name_show')." ".date('Y') }}. All rights reserved.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
