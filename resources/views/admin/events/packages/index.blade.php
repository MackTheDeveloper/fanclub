@extends('admin.layouts.master')
<title>Packages</title>

@section('content')
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar closed-sidebar">
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
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Packages</span>
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
                                                <a href="javascript:void(0);">Packages</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Package List  
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>                            
                        </div> 
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="{{url(config('app.adminPrefix').'/package/addPackage')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>New Package</button></a>
                                <button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm" id="exportPackages"><i class="fa fa-download btn-icon-wrapper"></i>Export</button>
                            </div>
                        </div>                     
                    </div>
                </div> 
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="package_listing" class="table table-hover table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Sr.No</th>
                                    <th>ID</th>
                                    <th>Event Name</th>
                                    <th>Package Name</th>
                                    <th>Price ({{ ($default_currency->currency_symbol != '') ? $default_currency->currency_symbol : '' }})</th>
                                    <th>Discounted Price ({{ $default_currency->currency_symbol }})</th>
                                    <th>Created At</th>
                                    <th>Is Active</th>                                    
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
    <!-- Modal for activating deactivating package -->
    <div class="modal fade" id="packageIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="packageIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="package_id" id="package_id">
                    <input type="hidden" name="is_active" id="is_active">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="packageIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal for delete event -->
     <div class="modal fade" id="packageDeleteModel" tabindex="-1" role="dialog" aria-labelledby="packageDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="package_id" id="package_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deletePackage">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/packages/package.js')}}"></script>
<script>
    let page_name = '<?php echo $page_name ; ?>'
</script>
@endpush
