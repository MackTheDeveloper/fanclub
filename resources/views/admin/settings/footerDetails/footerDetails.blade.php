@extends('admin.layouts.master')
<title>General Settings | {{config('app.name_show')}}</title>
<style>
    textarea.form-control {
        width: 500px;
        height: 150px !important;
    }
</style>
@section('content')
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar closed-sidebar">
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

                            <li class="nav-item"><a data-toggle="tab" href="#api_keys" class="active nav-link">SMS API Keys</a></li>
                            <li class="nav-item"><a data-toggle="tab" href="#social_links" class="nav-link">Social Media
                                    Links</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="api_keys" role="tabpanel">
                                @if(!isset($api_keys) || empty($api_keys))
                                <form id="addFooterDetailsForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/updateFooterDetails') }}">
                                    @csrf
                                    <input type="hidden" name="page_name" value="api_keys">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="api_key" class="font-weight-bold">API KEY</label>
                                                <div>
                                                    <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Enter API Key" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
                                    </div>
                                </form>
                                @else
                                <form id="updateFooterDetailsForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/updateFooterDetails') }}">
                                    @csrf
                                    <input type="hidden" name="page_name" value="api_keys">
                                    <input type="hidden" name="api_id" value="{{ $api_keys->id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="api_key" class="font-weight-bold">API KEY</label>
                                                <div>
                                                    <input type="text" class="form-control" id="api_key" name="api_key" placeholder="Enter API Key" value="{{ $api_keys->api_key }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
                                    </div>
                                </form>
                                @endif
                            </div>
                            <div class="tab-pane" id="social_links" role="tabpanel">
                                @if(!isset($social_links))
                                <form id="addSocialLinksForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/updateFooterDetails') }}">
                                    @csrf
                                    <input type="hidden" name="page_name" value="social_links">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fb_link" class="font-weight-bold">Facebook Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Enter FB Link" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="insta_link" class="font-weight-bold">Instagram Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="insta_link" name="insta_link" placeholder="Enter Instagram Link" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="youtube_link" class="font-weight-bold">Youtube Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="Enter Youtube Link" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="twitter_link" class="font-weight-bold">Twitter Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="twitter_link" name="twitter_link" placeholder="Enter Twitter Link" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
                                    </div>
                                </form>
                                @else
                                <form id="addSocialLinksForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/updateFooterDetails') }}">
                                    @csrf
                                    <input type="hidden" name="page_name" value="social_links">
                                    <input type="hidden" name="social_id" value="{{ $social_links->id }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fb_link" class="font-weight-bold">Facebook Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Enter FB Link" value="{{ $social_links->fb_link }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="insta_link" class="font-weight-bold">Instagram Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="insta_link" name="insta_link" placeholder="Enter Instagram Link" value="{{ $social_links->insta_link }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="youtube_link" class="font-weight-bold">Youtube Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="Enter Youtube Link" value="{{ $social_links->youtube_link }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="twitter_link" class="font-weight-bold">Twitter Link</label>
                                                <div>
                                                    <input type="text" class="form-control" id="twitter_link" name="twitter_link" placeholder="Enter Twitter Link" value="{{ $social_links->twitter_link }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/settings/footerDetails.js')}}"></script>
@endpush