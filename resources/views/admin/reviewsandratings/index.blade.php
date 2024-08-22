@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Reviews & Ratings </title>

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
                                        <i class="fa pe-7s-music"></i>
                                    </span>
                                    <span class="d-inline-block">Reviews & Ratings</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Reviews & Ratings</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                <a style="color: slategray">List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_genres_add') === true)
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/music-genres/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add Music Genre</button></a>
                            </div>
                        </div>
                        @endif --}}
                        <div class="page-title-actions">
                            <a href="javascript:void(0);" class="expand_collapse_filter">
                                <button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                    <i aria-hidden="true" class="fa fa-filter"></i> Filter
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
                        <div>
                            <form method="post" class="form-inline filter_form">
                                @csrf
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="daterange" class="mr-sm-2">Created Between Date</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="status_forum" class="mr-sm-2">Status</label>
                                    <select name="status_forum" id="status_forum" class="multiselect-dropdown form-control" style="width: 150px;">
                                        <option value="">Select Status</option>
                                        <option value="0">Pending</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="createdBy" class="mr-sm-2">Artist</label>
                                    <select name="createdBy" id="createdBy" class="multiselect-dropdown form-control" style="width: 190px;">
                                        <option value="">Select Artist</option>
                                        @foreach($users as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="type" class="mr-sm-2">Type</label>
                                    <select name="type" id="type" class="multiselect-dropdown form-control" style="width: 150px;">
                                        <option value="">Select Type</option>
                                        <option value="all">All</option>
                                        <option value="song">Song</option>
                                        <option value="artist">Artist</option>
                                    </select>
                                </div>
                                <button type="button" id="filter_forum" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr class="text-center">
                                <th style="display: none">ID</th>
                                <th>Action</th>
                                <th>Icon</th>
                                <th>Song/Artist</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Ratings</th>
                                <th>Review</th>
                                <th>Status</th>
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
    <form name="postSongCommentList" id="postSongCommentList" method="post" action="{{route('songComments')}}">
        @csrf
        <input type="hidden" name="song_id" id="clickSongId" value="">
    </form>
@endsection
@section('modals-content')
    <!-- Modal for activating deactivating template -->
    <div class="modal fade" id="forumIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="forumIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forumIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="forum_id" id="forum_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="forumIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="forumDeleteModel" tabindex="-1" role="dialog" aria-labelledby="forumDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forumDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="forum_id" id="forum_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteforum">Yes</button>
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
    <script src="{{asset('public/assets/js/reviewsandrating/reviewsandrating.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.expand_collapse_filter').on('click', function() {
                $(".expand_filter").toggle();
            })
        })
    </script>
@endpush
