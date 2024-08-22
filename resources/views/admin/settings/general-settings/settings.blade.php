<?php 
use App\Models\GlobalSettings;
?>
@extends('admin.layouts.master')
<title>General Settings | {{config('app.name_show')}}</title>
<style>
    /* textarea.form-control {
        width: 500px;
        height: 150px !important;
    } */
</style>
@section('content')
    @include('admin.include.header')
    <div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="fa pe-7s-global"></i>
                                    </span>
                                    <span class="d-inline-block">General Settings</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a>
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="javascript:void(0);">General Settings</a>
                                            </li>

                                            <li class="active breadcrumb-item" aria-current="page">
                                                Update
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">General Settings INFORMATION</h5>
                        <ul class="nav nav-tabs">

                            <li class="nav-item"><a data-toggle="tab" href="#contactdetails" class="active nav-link">Contact Details</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#social_links" class="nav-link">Social Media Links</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#app_links" class="nav-link">Apps Link</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#security_questions" class="nav-link">Security Questions</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#preferences" class="nav-link">Preferences</a></li>
                        </ul>

                        <div class="tab-content">
                            @include('admin.settings.general-settings.contactDetails')
                            @include('admin.settings.general-settings.socialMedia')
                            @include('admin.settings.general-settings.appsLink')
                            @include('admin.settings.general-settings.securityQuestions')
                            @include('admin.settings.general-settings.preferences')
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/settings/footerDetails.js')}}"></script>
@endpush