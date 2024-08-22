@extends('admin.layouts.master')
@section('title','Artists')
@section('content')
    @include('admin.include.header')
	<div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer" style="width: 100%;">
            <div class="app-main__inner">
                <div class="app-page-title app-page-title-simple">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Artists</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Artists</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                              <a style="color:grey">Artist List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/artist/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add Artist</button></a>
                            </div>
                            <!-- <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/user/export')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-download btn-icon-wrapper"></i>Export</button></a>
                            </div> -->
                            <!-- <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/user/import')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-upload btn-icon-wrapper"></i>Import</button></a>
                            </div> -->
                        </div>
                        <div>
                            <a href="{{ url(config('app.adminPrefix').'/professional/import') }}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                <i aria-hidden="true" class="fa fa-import"></i> Import
                            </button></a>
                            <!-- <span><a href="javascript:void(0);" class="expand_collapse_filter"><i aria-hidden="true" class="fa fa-filter" style="margin-bottom: 10px;font-size: 25px;"></i></a></span> -->
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="expand_collapse_filter"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                <i aria-hidden="true" class="fa fa-filter"></i> Filter
                            </button></a>
                            <!-- <span><a href="javascript:void(0);" class="expand_collapse_filter"><i aria-hidden="true" class="fa fa-filter" style="margin-bottom: 10px;font-size: 25px;"></i></a></span> -->
                        </div>
                    </div>
                </div>
                <!-- <div>
                    <nav class="" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                            <li class="active breadcrumb-item" aria-current="page">Users List</li>
                        </ol>
                    </nav>
                </div>                                            -->
                <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i>  Filter</h5>
                        <div>
                            <form method="post" class="form-inline">
                                @csrf
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="filter_date" class="mr-sm-2">Select Date Range</label>
                                    <input type="text" class="form-control" name="daterange" id="daterange" />
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="category_id" class="mr-sm-2">Professional Cateogry</label>
                                    <?php echo Form::select('professional_category_id', $professionalCategories, '', ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'professional_category_id']); ?>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="category_id" class="mr-sm-2">Cateogry</label>
                                    <?php echo Form::select('category_id', $productCategories, '', ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'category_id']); ?>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="is_subscribed_filter" class="mr-sm-2">Is Subscribed?</label>
                                    <select name="is_subscribed_filter" id="is_subscribed_filter" class="form-control">
                                        <option value="">All</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="status" class="mr-sm-2">Is Active?</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">All</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <button type="button" id="search_role" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                    <h5 class="card-title">List Of User</h5>
                        <table style="width: 100%;" id="user_list" class="table table-hover table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Artist Category</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Is Subscribed</th>
                                        <th>Is Active</th>
                                        <!-- <th>Is Deleted</th> -->
                                        <th>Action</th>
                                    </tr>
                                </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
@section('modals-content')
<!-- Modal Start -->
<div class="modal fade bd-example-modal-sm" id="userIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="userIsActiveModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userIsActiveModelLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" id="user_id">
                <input type="hidden" name="is_active" id="is_active">
                <p class="mb-0" id="message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="userIsActive">Yes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-sm" id="userIsSubscribedModel" tabindex="-1" role="dialog" aria-labelledby="userIsActiveModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userIsActiveModelLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" id="user_id">
                <input type="hidden" name="is_subscribed" id="is_subscribed">
                <p class="mb-0" id="message2"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="userIsSubscribed">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Over -->
<!-- Modal Start -->
<div class="modal fade bd-example-modal-sm" id="deleteUserModel" tabindex="-1" role="dialog" aria-labelledby="deleteUserModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModelLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" id="user_id">
                <input type="hidden" name="is_deleted" id="is_deleted">
                <p class="mb-0" id="delete_message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="userDelete">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Over -->
@endsection
@push('scripts')
<script src="{{asset('public/assets/custom/datatables/user/professional-list-datatable.js')}}"></script>
<script>
$(document).ready(function(){
    $('.expand_collapse_filter').on('click', function(){
        $(".expand_filter").toggle();
    })
})
</script>
@endpush
