@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Songs </title>

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
                                    <span class="d-inline-block">Songs</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Songs</a>
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
                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="category" class="mr-sm-2">Category</label>
                                    <select name="category" id="category" class="multiselect-dropdown form-control" style="width: 200px;">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="genre" class="mr-sm-2">Genre</label>
                                    <select name="genre" id="genre" class="multiselect-dropdown form-control" style="width: 200px;">
                                        <option value="">Select Genre</option>
                                        @foreach($genres as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="language" class="mr-sm-2">Language</label>
                                    <select name="language" id="language" class="multiselect-dropdown form-control" style="width: 200px;">
                                        <option value="">Select Language</option>
                                        @foreach($languages as $key=>$val)
                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="artist" class="mr-sm-2">Artist</label>
                                    <select name="artist" id="artist" class="multiselect-dropdown form-control" style="width: 200px;">
                                        <option value="">Select Artist</option>
                                        @foreach($artists as $key=>$val)
                                        <option {{($artist_id && $artist_id==$val->id)?"selected='selected'":''}} value="{{$val->id}}">{{$val->firstname.' '.$val->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="daterange" class="mr-sm-2">Created Between Date</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div> --}}
                                <button type="button" id="filter_song" class="btn btn-primary">Search</button>
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
                                    <th>Name</th>
                                    <th>Artist</th>
                                    <th>Icon</th>
                                    {{-- <th>Song Picture / Icon</th> --}}
                                    <th>File Type</th>
                                    <th>Duration</th>
                                    <th>Categories</th>
                                    <th>Genres</th>
                                    <th>Languages</th>
                                    <th>Release Date</th>
                                    <th>Date Added</th>
                                    <th># Likes</th>
                                    <th># Views</th>
                                    <th># Downloads</th>
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
{{-- <!-- Modal for activating deactivating template -->
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
    </div> --}}

    <!-- Modal for delete template -->
    <div class="modal fade" id="songDeleteModel" tabindex="-1" role="dialog" aria-labelledby="songDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="songDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="song_id" id="song_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deletesong">Yes</button>
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
<script>
    let dashboardSearch = '{{$search}}';
    $('#showDropdown').val(dashboardSearch);
    $('#searchableFormListing').attr('action', base_url + '/securefcbcontrol/songs/index');
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script src="{{asset('public/assets/js/music_management/songs.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush
