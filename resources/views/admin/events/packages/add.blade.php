@extends('admin.layouts.master')
<title>Add Package</title>

@section('content')
<div class="app-container body-tabs-shadow fixed-header fixed-sidebar app-theme-gray closed-sidebar">
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
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Packages</span>
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
                                                <a href="javascript:void(0);">Packages</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/package/list')}}">Package List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Add Package
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
                        <h5 class="card-title">Create Package</h5>
                        <form id="addPackageForm" class="col-md-10 mx-auto" method="post"
                            action="{{ url(config('app.adminPrefix').'/package/addPackage') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="event_id" class="font-weight-bold">Event Name</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select class="js-states browser-default form-control w-100 multiselect-dropdown" name="event_id"
                                                id="event_id">
                                                <option value="" disabled selected>Select Event</option>
                                                @foreach($events as $event)
                                                <option value="{{ $event->id }}">
                                                    {{ $event->event_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_name" class="font-weight-bold">Package Name</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="package_name"
                                                name="package_name" placeholder="Enter Package Name" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="font-weight-bold">Price ({{ $default_currency->currency_symbol }})</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="number" class="form-control" id="price" name="price"
                                                placeholder="Enter Price" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discounted_price" class="font-weight-bold">Discounted Price ({{ $default_currency->currency_symbol }})</label>
                                        <div>
                                            <input type="number" class="form-control" id="discounted_price"
                                                name="discounted_price" placeholder="Enter Discounted Price" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                            <select name="is_active" id="is_active" class="form-control">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="other_details" class="font-weight-bold">Other Details
                                        </label>
                                        <div>
                                            <textarea name="other_details" id="other_details" type="text"
                                                class="form-control ckeditor"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-2 offset-md-4">
                                                <button type="submit" class="btn btn-primary btn-shadow w-100"
                                                    id="add_pkg_btn">Add Package</button>
                                            </div>
                                            <div class="col-md-2">
                                                <a href="{{ url(config('app.adminPrefix').'/package/list') }}">
                                                    <button type="button" class="btn btn-light btn-shadow w-100"
                                                        name="cancel" value="Cancel">Cancel</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('public/assets/js/packages/package.js')}}"></script>
<script>
    let page_name = '<?php echo $page_name ; ?>'

</script>
@endpush
