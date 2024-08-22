@extends('admin.layouts.master')
<title>List Customer | fanclub</title>

@section('content')
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar closed-sidebar">
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
                                    <span class="d-inline-block">Customer</span>
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
                                                <a href="javascript:void(0);">Customer</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Customer List  
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>                            
                        </div> 
                        <div class="page-title-actions">                            
                            <!-- <div class="d-inline-block dropdown">                               
                                <a href="{{url(config('app.adminPrefix').'/user/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>New Role</button></a>
                            </div> -->
                            <div class="d-inline-block dropdown">                               
                                <a href="{{url(config('app.adminPrefix').'/customer/export')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-download btn-icon-wrapper"></i>Export</button></a>
                            </div>
                            <!-- <div class="d-inline-block dropdown">                               
                                <a href="{{url(config('app.adminPrefix').'/customer/import')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-upload btn-icon-wrapper"></i>Import</button></a>
                            </div> -->
                        </div>                                                  
                    </div>
                </div>                                                            
                <div class="main-card mb-3 card">
                    <div class="card-body">
                    <h5 class="card-title">List Of Customer</h5>                                                  
                        <table style="width: 100%;" id="customer_list" class="table table-hover table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Unique ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>IP Address</th>
                                        <th>OS Name</th>
                                        <th>Browser & Version</th>                                        
                                        <th>Created At</th>
                                        <th>Is Active</th>                                        
                                        <th>Is Verified</th>                                        
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
    <!-- Modal Start -->
    <div class="modal fade" id="customerIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="customerIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="customer_id" id="customer_id">
                    <input type="hidden" name="is_active" id="is_active">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="customerIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Over -->
     <!-- Modal Start -->
     <div class="modal fade bd-example-modal-sm" id="deleteCustomerModel" tabindex="-1" role="dialog" aria-labelledby="deleteCustomerModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCustomerModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="customer_id" id="customer_id">
                    <input type="hidden" name="is_deleted" id="is_deleted">
                    <p class="mb-0" id="delete_message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="customerDelete">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Over -->
</div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')
<script src="{{asset('public/assets/custom/datatables/user/customer-list-datatable.js')}}"></script>
@endpush