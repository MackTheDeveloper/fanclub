@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | News </title>

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
                                    <span class="d-inline-block">News</span>
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
                                                <a href="{{ url(config('app.adminPrefix').'/artists/index') }}" style="color: grey">Artist</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                               <a style="color: slategray">{{$model->firstname}}'s News</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fans_add') === true)
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/fan/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add Fan</button></a>
                            </div>
                        </div>
                        @endif --}}
                        {{-- <div class="page-title-actions">
                            <a href="javascript:void(0);" class="expand_collapse_filter">
                                <button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                    <i aria-hidden="true" class="fa fa-filter"></i> Filter
                                </button>
                            </a>
                        </div> --}}
                    </div>
                </div>
                {{-- <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
                        <div>
                            <form method="post" class="form-inline">
                                @csrf
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="is_active" class="mr-sm-2">Status</label>
                                    <select name="is_active" id="is_active" class="multiselect-dropdown form-control" style="width: 150px;">
                                        <option value="">Select Status</option>
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="daterange" class="mr-sm-2">Created Between Date</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div>
                                <button type="button" id="search_fan" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div> --}}
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">Action</th>
                                    <th class="text-left">Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Date</th>
                                    {{-- <th class="text-center">Status</th> --}}
                                    <th class="text-center">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key=>$row)
                                <tr class="{{$row->status?"":"row_inactive"}}">
                                    <td class="text-center">
                                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fan_playlist_song_listing') || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fan_playlist_delete'))
                                            <div class="d-inline-block dropdown">
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-cog fa-w-20"></i>
                                                    </span>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-left">
                                                    <ul class="nav flex-column">
                                                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fan_playlist_delete'))
                                                            <li class="nav-item">
                                                                <a class="nav-link fan_delete" data-id="{{$row->id}}" href="javascript:void(0)" title="delete">Delete</a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-left">{{$row->name}}</td>
                                    <td class="text-left">{{$row->description}}</td>
                                    <td class="text-center">{{getFormatedDate($row->date)}}</td>
                                    <td class="text-center">{{getFormatedDate($row->created_at)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
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
    <div class="modal fade" id="fanPlaylistIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="fanIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fanIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="fan_playlist_id" id="fan_playlist_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="fanPlaylistIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="fanPlaylistDeleteModel" tabindex="-1" role="dialog" aria-labelledby="fanPlaylistDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fanPlaylistDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="fan_playlist_id" id="fan_playlist_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deletefanPlaylist">Yes</button>
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
{{-- fanPlaylist --}}
<script src="{{asset('public/assets/js/users/artistNews.js')}}"></script>
<script>
    $('#Tdatatable').DataTable({
        language: {
            searchPlaceholder: "Search By Name... "
        },
        "columnDefs": [
            {
                targets: [0],
                className: "opacity1 text-center"
            },
            {
                targets: [1,2,3],
                className: "text-left",
            },
            {
                targets: [0],
                "orderable": false
            }
        ],
        "order": [[3, "asc"]],
    });
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush
