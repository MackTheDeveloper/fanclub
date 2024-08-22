@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Fans </title>

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
                                        <i class="fa fa-user opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Fans</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Fans</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                               <a style="color: slategray">List</a>
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
                        <div class="page-title-actions">
                            {{ Form::open(array('url' => route('exportFans'),'class'=>'exportFans','id'=>'exportFans','autocomplete'=>'off')) }}
                                <input type="hidden" name="status" id="status">
                                <input type="hidden" name="startDate" id="startDate">
                                <input type="hidden" name="endDate" id="endDate">
                                <input type="hidden" name="search" id="search">
                                <input type="hidden" name="subscription" id="subscription">
                                <input type="hidden" name="country" id="country">
                            {{ Form::close() }}
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fans_export') === true)
                                <a id="exportBtn" class="mb-2 mr-2 btn-icon btn-square btn btn-danger btn-sm" tabindex="0"  href="#"><i class="fas fa-file-excel"></i>&nbsp&nbsp Excel</a>
                            @endif
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
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="subscription_filter" class="mr-sm-2">Subscription</label>
                                    <select name="subscription_filter" id="subscription_filter" class="multiselect-dropdown form-control" style="width: 150px;">
                                        <option value="">Select Subscription</option>
                                        @foreach ($subscriptions as $key=>$val)
                                            <option value="{{$key}}">{{$val}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="country_filter" class="mr-sm-2">Country</label>
                                    <select name="country_filter" id="country_filter" class="multiselect-dropdown form-control" style="width: 150px;">
                                        <option value="">Select Country</option>
                                        @foreach ($country as $key=>$val)
                                            <option value="{{$val['value']}}">{{$val['value'] ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                        <button type="button" id="search_fan" class="btn btn-primary">Search</button>
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
                                    <th>Profile Pic</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Prefix</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>Gender</th>
                                    <th>Subscription</th>
                                    <th>Status</th>
                                    <th>DOB</th>
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
    <div class="modal fade" id="fanIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="fanIsActiveModelLabel" aria-hidden="true">
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
                    <input type="hidden" name="fans_id" id="fans_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="fanIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="fanDeleteModel" tabindex="-1" role="dialog" aria-labelledby="fanDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fanDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="fan_id" id="fan_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deletefan">Yes</button>
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
    $('#searchableFormListing').attr('action', base_url + '/securefcbcontrol/fans/index');
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<script src="{{asset('public/assets/js/users/fan.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
    $(document).on('click','#exportBtn',function(){
        // var startDate = $('#daterange').data('daterangepicker').startDate;
        // var endDate = $('#daterange').data('daterangepicker').endDate;
        // var status = $('#is_active').val();
        // var search = $('.dataTables_filter input[type="search"]').val();
        // startDate = startDate.format('YYYY-MM-DD');
        // endDate = endDate.format('YYYY-MM-DD');
        // $('#exportFans #startDate').val(startDate);
        // $('#exportFans #endDate').val(endDate);
        // $('#exportFans #status').val(status);
        // $('#exportFans #search').val(search);
        $('#exportFans').submit();
    })
</script>
@endpush
