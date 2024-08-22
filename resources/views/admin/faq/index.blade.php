@extends('admin.layouts.master')
<title>{{config('app.name_show')}} |
    FAQ  </title>

@section('content')
    @include('admin.include.header')
    <div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title app-page-title-simple">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                    <i class="active_icon metismenu-icon pe-7s-home"></i>
                                    </span>
                                    <span class="d-inline-block">FAQ </span>
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
                                                <a href="javascript:void(0);" style="color: grey">FAQ </a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                <a style="color: slategray">List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_add') === true)
                            <div class="page-title-actions">
                                <div class="d-inline-block dropdown">
                                    <a href="{{url(config('app.adminPrefix').'/faq/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add FAQ</button></a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr class="text-center">
                                <th style="display: none">ID</th>
                                <th>Action</th>
                                <th>Type</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>

    </div>
@endsection
@section('modals-content')
    <!-- Modal for activating deactivating template -->
    <div class="modal fade" id="musicGenresIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="musicGenresIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="musicGenresIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="music_genres_id" id="music_genres_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="musicGenresIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="musicGenresDeleteModel" tabindex="-1" role="dialog" aria-labelledby="musicGenresDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="musicGenresDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="music_genres_id" id="music_genres_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deletemusicGenres">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .hide_column {
        display: none;
    }
</style>

@push('scripts')
    <script src="{{asset('public/assets/js/settings/faqs.js')}}"></script>
@endpush
