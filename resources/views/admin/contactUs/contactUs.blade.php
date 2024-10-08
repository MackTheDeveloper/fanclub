@extends('admin.layouts.master')
<title>Contact Enquiries | {{config('app.name_show')}}</title>
@section('content')
<script type="text/javascript">
    var baseUrl = <?php echo json_encode($baseUrl); ?>;
</script>
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
                                        <i class="fa pe-7s-headphones"></i>
                                    </span>
                                    <span class="d-inline-block">Contact Enquiries</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Contact Enquiries</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                              <a style="color: slategray">List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <button class="btn btn-square btn-primary btn-sm" id="divFilterToggle"> <i aria-hidden="true" class="fa fa-filter"></i> Filter </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-card mb-3 card" id="divFilter">
                    <div class="card-body">
                        <h5 class="card-title"><i style="color: grey" aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
                        <form class="form-inline" id="filterForm">
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="filter_date" class="mr-sm-2">Select Date Range</label>
                                <input type="text" class="form-control" name="daterange" id="daterange" />
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="name" class="mr-sm-2">Name</label>
                                <input type="text" class="form-control" name="name" id="name" />
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="name" class="mr-sm-2">Email</label>
                                <input type="text" class="form-control" name="email" id="email" />
                            </div>
                            <button type="button" id="searchButton" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table class="display nowrap table table-hover table-striped table-bordered" id="tblContactUs" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Ip Address</th>
                                    <th>Created At</th>

                                </tr>
                            </thead>
                            <tbody>
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

<!-- Modal -->
<div class="modal" id="contactUsReplyModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contact Us Reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="">
                    <input type="hidden" name="inquiryId" id="inquiryId" readonly="true">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class="">Header</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <input type="text" class="form-control" name="customerName" id="customerName" readonly="true" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class="">Customer Message</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <textarea name="customerMessage" id="customerMessage" placeholder="Description" type="text" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class=""><span class="text-danger">*</span>Reply</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <textarea name="replyMessage" id="replyMessage" placeholder="reply here..." type="text" class="form-control ckeditor"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" name="reply" value="reply" id="reply">Reply</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                            <!-- <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Start -->
<div class="modal" id="contactUsDeleteModel" tabindex="-1" role="dialog" aria-labelledby="userIsActiveModelLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="inquiryIdForDelete" id="inquiryIdForDelete">
                <p class="mb-0" id="message">Are you Sure?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal" id="noDelete">No</button>
                <button type="button" class="btn btn-primary" id="confirmDelete">Yes</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Over -->

<!-- Modal -->
<div class="modal" id="contactUsMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Contact Us Reply View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="">
                    <!-- <input type="hidden" name="inquiryId" id="inquiryId" readonly="true"> -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class="">Header</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <p id="customerNameView"></p>
                                <!-- <input type="text" class="form-control" name="customerName" id="customerName" readonly="true" /> -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class="">Customer Message</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <p id="customerMessageVew"></p>
                                <!-- <textarea name="customerMessage" id="customerMessage" placeholder="Description" type="text" class="form-control"></textarea> -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="examplePassword" class="">Reply Message</label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="position-relative form-group">
                                <p id="replyMessageView"></p>
                                <!-- <textarea name="replyMessage" id="replyMessage" type="text" class="form-control ckeditor"></textarea> -->
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-light" data-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/contactUs/contactUs.js?t=20220810')}}"></script>
@endpush
