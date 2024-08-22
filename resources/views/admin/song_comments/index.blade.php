@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Song Comments </title>

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
                                    <span class="d-inline-block">Song Comments</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Song Comments</a>
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
                            <form method="post" class="form-inline">
                                @csrf
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="daterange" class="mr-sm-2">Commented Between</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div>
                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="artists" class="mr-sm-2">Artists</label>
                                    <select name="artists[]" id="artists" multiple="multiple" class="multiselect-dropdown form-control" style="width: 200px;">
                                        {{-- <option value="">Select Artist</option> --}}
                                        @foreach($artist as $key=>$val)
                                        <option {{($artist_id && $artist_id==$val->id)?"selected='selected'":''}} value="{{$val->id}}">{{$val->firstname.' '.$val->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="songs" class="mr-sm-2">Songs</label>
                                    <select name="songs" id="songs"  multiple="multiple" class="multiselect-dropdown form-control" style="width: 350px;">
                                        {{-- <option value="">Select Songs</option> --}}
                                        @if($songs)
                                            @foreach($songs as $key=>$val)
                                            <option {{($song_id && $song_id==$val->id)?"selected=selected":''}} value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="daterange" class="mr-sm-2">Created Between Date</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div> --}}
                                <button type="button" id="filter_comments" class="btn btn-primary">Search</button>
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
                                    <th>User Name</th>
                                    <th>Song Name</th>
                                    <th>Comments</th>
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
<!-- Modal for showing comment template -->
    <div class="modal fade" id="commentShowModel" tabindex="-1" role="dialog" aria-labelledby="commentShowModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentShowModelLabel">Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="showCommentText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="commentDeleteModel" tabindex="-1" role="dialog" aria-labelledby="commentDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="comment_id" id="comment_id">
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
<script src="{{asset('public/assets/js/music_management/song_comments.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush
