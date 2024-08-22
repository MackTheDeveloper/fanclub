@extends('admin.layouts.master')
<title>Update Event | fanclub</title>

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
                                    <span class="d-inline-block">Events</span>
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
                                                <a href="javascript:void(0);">Events</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/event/list')}}">Events List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Update Event
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Update Event</h5>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form id="event_edit_form" class="col-md-10 mx-auto" method="post"
                        action="{{url(config('app.adminPrefix').'/event/updateEvent')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $event->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_name" class="font-weight-bold">Event Name</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="text" class="form-control" id="event_name" name="event_name"
                                            value="{{ $event->event_name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active" class="font-weight-bold">Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="1" {{ ( $event->is_active == 1 ) ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ ( $event->is_active == 0 ) ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="event_image" class="font-weight-bold">Event Image</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="file" class="form-control" id="event_image" name="event_image"
                                            value="{{old('event_image')}}">                                      
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            @if (!empty($event->event_image))
                                        <img src="{{ asset('public/assets/images/events/' . $event->event_image) }}"
                                            alt="{{$event->event_image}}" width="100" height="100" class="mb-3"
                                            id="selected_event_image" />
                                        @else
                                        <img src="" width="100" height="100" class="mb-3" id="selected_event_image">
                                        @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="event_desc" class="font-weight-bold">Event Description</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <textarea name="event_desc" id="event_desc" type="text"
                                            class="form-control ckeditor">{{ $event->event_desc }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div id="ck_error"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2 offset-md-4">
                                            <button type="submit" class="btn btn-primary btn-shadow w-100">Update
                                                Event</button>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{ url(config('app.adminPrefix').'/event/list') }}">
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
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/events/event.js')}}"></script>
@endpush
