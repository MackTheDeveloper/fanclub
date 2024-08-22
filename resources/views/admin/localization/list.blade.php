@extends('admin.layouts.master')
<title>List of Locales | fanclub</title>

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
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Localization</span>
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
                                                <a href="javascript:void(0);">Localization</a>
                                            </li>
                                            <!-- <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/language/list')}}">Language</a>
                                            </li> -->
                                            <li class="active breadcrumb-item" aria-current="page">
                                                List Locale  
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>                            
                        </div>                        
                        <div class="page-title-actions">                            
                            <div class="d-inline-block dropdown">                               
                                <a href="{{url(config('app.adminPrefix').'/locale/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add Locale</button></a>
                                
                                <a href="javascript:void(0);" class="expand_collapse_filter"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                <i aria-hidden="true" class="fa fa-filter"></i> Filter
                            </button></a>
                            </div>                                                        
                        </div>                                                 
                    </div>
                </div> 
                <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i>  Filter</h5>                        
                        <div>
                            <form method="post" class="form-inline" id="locale_filter">  
                                @csrf                                                        
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="filter_role" class="mr-sm-2">Code</label>
                                    <input type="text" name="code" id="code" class="form-control">
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                    <label for="filter_role" class="mr-sm-2">Title</label>
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                                <button type="button" id="search_locale" class="btn btn-primary">Search</button>
                            </form>                            
                        </div>
                    </div>
                </div>                 
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">List Of Locale</h5>
                        <table style="width: 100%;" id="locale_list" class="table table-hover table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Created At</th>
                                        <!-- <th>Is Active</th>                                         -->
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
    <!-- Modal Start -->
    <div class="modal fade bd-example-modal-sm" id="localeDeleteModel" tabindex="-1" role="dialog" aria-labelledby="localeDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="localeDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="locale_id" id="locale_id">                    
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteLocale">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Over -->    
@endsectoin
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')
<script src="{{asset('public/assets/custom/datatables/locale/list-locale-datatable.js')}}"></script>
<script>
$(document).ready(function(){
    $('.expand_collapse_filter').on('click', function(){
        $(".expand_filter").toggle();
    })    
})
</script>
@endpush