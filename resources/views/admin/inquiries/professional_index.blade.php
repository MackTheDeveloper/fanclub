@extends('admin.layouts.master')
@section('title','Professional Enquiries')
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
                                        <i class="fa pe-7s-cart"></i>
                                    </span>
                                    <span class="d-inline-block">Professional Enquiries</span>
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
                                                <a href="javascript:void(0);">Professional Enquiries</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                List
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <a href="javascript:void(0);" class="expand_collapse_filter"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                    <i aria-hidden="true" class="fa fa-filter"></i> Filter
                                </button></a>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
                        <div>

                            {{ Form::open(array('url' => '','class'=>'form-inline','id'=>'','autocomplete'=>'off')) }}
                            @csrf
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="filter_date" class="mr-sm-2">Select Date Range</label>
                                <input type="text" class="form-control" name="daterange" id="daterange" />
                            </div>
                            <!-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">From</label>
                                <input type="text" name="from_date_filter" placeholder=" -- From Date" class="form-control datepicker from_date_filter">
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">To</label>
                                <input type="text" name="to_date_filter" placeholder=" -- To Date" class="form-control datepicker to_date_filter">
                            </div> -->
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="category_id" class="mr-sm-2">Professionals</label>
                                <?php echo Form::select('category_id', $products, '', ['class' => 'form-control ', 'placeholder' => 'Select Professional', 'id' => 'category_id']); ?>
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="status" class="mr-sm-2">Status</label>
                                <select name="status" id="status" class="form-control col-8">
                                    <option value="">All</option>
                                    <option value="0">Pending</option>
                                    <option value="2">In Progress</option>
                                    <option value="1">Closed</option>
                                </select>
                            </div>
                            <button type="button" id="filter_products" class="btn btn-primary">Search</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th style="display: none">ID</th>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Professional Name</th>
                                    <th>Message</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
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
    <div class="modal fade" id="productsIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="productsIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="products_id" id="products_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="productsIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="productsDeleteModel" tabindex="-1" role="dialog" aria-labelledby="productsDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="products_id" id="products_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteProducts">Yes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .hide_column {
        display: none;
    }
</style>
@endpush
@push('scripts')
<script>
    let checkRecord = '';
</script>
<script src="{{asset('public/assets/js/inquiries/professionalinquiries.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush