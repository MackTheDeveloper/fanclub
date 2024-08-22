@extends('admin.layouts.master')
@section('title', 'Dashboard')

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
                                    <i class="lnr-apartment opacity-6"></i>
                                </span>
                                <span class="d-inline-block">Dashboard</span>
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
                                            <a>Dashboard</a>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">New Artists</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$totalArtistToday}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-user-tie"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">New Fans</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$totalFanToday}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Today's Subscriptions</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$totalSubscriptionToday}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-money-bill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Today's Sales</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div>
                                            {{$totalSubscriptionSumToday}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-money-bill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card expand_filter">
                <h5 class="card-header" style="display: grid">
                    <a data-toggle="collapse" href="#collapse-example" aria-expanded="true" aria-controls="collapse-example" id="heading-example" class="d-block">
                        <i class="fa fa-chevron-down pull-right"></i>
                        Filter
                    </a>
                </h5>
                <div id="collapse-example" class="collapse show" aria-labelledby="heading-example">
                    <div class="card-body">
                        <form id="filterDashboardForm" method="post" class="form-inline">
                            @csrf
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="from_date" class="mr-sm-2">From Date</label>
                                <!-- <input type="text" name="from_date" id="from_date" class="form-control" value="{{ date('m/01/Y') }}" /> -->
                                <input type="text" name="from_date" id="from_date" class="form-control" value="{{ date('m/d/Y',strtotime("-1 days")) }}" />
                                
                                <div id="from_date_error" style="color: red;"></div>
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="to_date" class="mr-sm-2">To Date</label>
                                <!-- <input type="text" name="to_date" id="to_date" class="form-control" value="{{ date('m/d/Y') }}" /> -->
                                <input type="text" name="to_date" id="to_date" class="form-control" value="{{ date('m/d/Y',strtotime("-1 days")) }}" />
                            </div>
                            <button type="button" id="filter_dashboard_count" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Artists</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div id="filter_artist">
                                            {{$filterTotalArtist}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-user-tie"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Fans</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div id="filter_fans">
                                            {{$filterTotalFans}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Subscriptions</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div id="filter_subscription">
                                            {{$filterTotalSubscription}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-money-bill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-primary card">
                        <div class="widget-chat-wrapper-outer">
                            <div class="widget-chart-content">
                                <div class="widget-title opacity-5 text-uppercase">Sales</div>
                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                    <div class="widget-chart-flex align-items-center">
                                        <div id="filter_sales">
                                            {{$filterTotalSubscriptionSum}}
                                        </div>
                                        <div class="widget-title ml-auto font-size-lg font-weight-normal text-muted">
                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                <i style="font-size: 32px;" class="fa fa-money-bill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-7 col-lg-8">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal" style="display: block;width:100%">Customers vs Sales
                                <div style="float: right">
                                    <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo Form::radio('result', 'daily',true,['class' => 'custom-control-input radioBtnDuration','id'=>'daily']); ?>
                                        <label class="custom-control-label" for="daily">Daily</label>
                                      </div>
                                      <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo Form::radio('result', 'monthly',false,['class' => 'custom-control-input radioBtnDuration','id'=>'monthly']); ?>
                                        <label class="custom-control-label" for="monthly">Monthly</label>
                                      </div>
                                    <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo Form::radio('result', 'yearly',false,['class' => 'custom-control-input radioBtnDuration','id'=>'yearly']); ?>
                                        <label class="custom-control-label" for="yearly">Yearly</label>
                                      </div>
                                </div>
                                {{-- <input style="margin-right: 5px" type="radio" name="result" value="daily" id="daily" class="radioBtnDuration" checked><label style="margin-right: 10px" for="daily">Daily</label>
                                <input style="margin-right: 5px" type="radio" name="result" value="monthly" id="monthly" class="radioBtnDuration"><label style="margin-right: 10px" for="monthly">Monthly</label>
                                <input style="margin-right: 5px" type="radio" name="result" value="yearly" id="yearly" class="radioBtnDuration"><label style="margin-right: 10px" for="yearly">Yearly</label> --}}
                            </div>

                            <!-- <div class="btn-actions-pane-right text-capitalize">
                                    <button class="btn btn-warning">Actions</button>
                                </div> -->
                        </div>
                        <div class="pt-0 card-body">
                            <div id="monthly-sales-graph"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 col-lg-4">
                    <div class="mb-3 card">
                        <div class="card-header-tab card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Customer Reviews</div>
                            <!-- <div class="btn-actions-pane-right text-capitalize">
                                    <button class="btn btn-warning">Actions</button>
                                </div> -->
                        </div>
                        <div class="pt-0 card-body" style="margin-top: 20px">
                            <div id="review-graph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="text-left mb-3 border-primary card">
                        <div class="card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Top 5 Songs</div>
                        </div>
                        <div class="card-body">
                            <table id="TdatatableTopSongs" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="">
                                        <th>Icon</th>
                                        <th>Song Name</th>
                                        <th>Artist</th>
                                        <th>#Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topSongs as $topSongs)
                                    <tr>
                                        <td><img width="50" height="50" src="{{ $topSongs->icon }}" /></td>
                                        <td>{{ $topSongs->name }}</td>
                                        <td>{{ $topSongs->artist->firstname.' '.$topSongs->artist->lastname }}</td>
                                        <td>{{ $topSongs->num_streams }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-left mb-3 border-primary card">
                        <div class="card-header">
                            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Top 5 Artists</div>
                        </div>
                        <div class="card-body">
                            <table id="TdatatableTopArtists" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr class="">
                                        <th>Profile Pic</th>
                                        <th>Artist</th>
                                        <th>#Subscribers</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topArtists as $topArtists)
                                    <tr>
                                        <td><img width="50" height="50" src="<?php echo app('App\Models\UserProfilePhoto')->getProfilePhoto($topArtists->id) ?>" /></td>
                                        <td>{{ $topArtists->firstname.' '.$topArtists->lastname }}</td>
                                        <td>{{ !empty($topArtists->no_subscription) ? $topArtists->no_subscription : '0' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Pending Approvals</div>
                </div>
                <div class="card-body">
                    <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                {{-- <th style="display: none">ID</th> --}}
                                <th>Action</th>
                                <th>Profile Pic</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Country</th>
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

<div class="modal fade" id="artistIsApproveModel" tabindex="-1" role="dialog" aria-labelledby="artistIsApproveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="artistIsApproveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="artist_id" id="artist_id">
                    <input type="hidden" name="approve" id="approve">
                    <p class="mb-0" id="messageApprove"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="artistIsApprove">Yes</button>
                </div>
            </div>
        </div>
    </div>


<!-- Modal for delete template -->
<div class="modal fade" id="artistDeleteModel" tabindex="-1" role="dialog" aria-labelledby="artistDeleteModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="artistDeleteModelLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="artist_id" id="artist_id">
                <p class="mb-0" id="message_delete"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="deleteartist">Yes</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
{{-- <script>
    let positiveReview = <?php echo $totalPositiveReview; ?>;
    let lastFifteenDays = <?php echo json_encode($lastFifteenDays); ?>;
    let totalFrontendUsersInLastFifteenDays = <?php echo json_encode($totalFrontendUsersInLastFifteenDays); ?>;
    let totalProfessionalsInLastFifteenDays = <?php echo json_encode($totalProfessionalsInLastFifteenDays); ?>;
</script> --}}
<script src="{{asset('public/assets/js/dashboard/dashboard.js')}}"></script>
@endpush
