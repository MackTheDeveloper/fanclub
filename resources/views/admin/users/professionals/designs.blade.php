@extends('admin.layouts.master')
@section('title','Professional Designs')
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
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Professionals</span>
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
                                                <a href="javascript:void(0)">Professionals</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/professional/list')}}">List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Designs
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>                            
                        </div>                          
                    </div>
                </div>                                                        
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Professonal Details</h5>
                            <form id="addNewProfessional" method="post" action="{{url(config('app.adminPrefix').'/professional/designs/'.$user->id)}}" enctype="multipart/form-data">
                            @csrf             
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="form-group">
                                    <div class="file-loading">
                                        <input id="file-1" type="file" name="file[]" multiple>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div> 
                
            </div>
            @include('admin.include.footer')
        </div>
    </div>
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@endsection
@push('styles')
<style type="text/css">
.kv-zoom-cache{
    display: none;    
}
    
</style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>

        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/> -->

@endpush
@push('scripts')
    <script src="{{asset('/public/assets/custom/kartik-v-bootstrap-fileinput/js/fileinput.min.js')}}"></script>

    <script src="{{asset('/public/assets/custom/kartik-v-bootstrap-fileinput/themes/fas/theme.min.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        var issubscribed = @json($user->is_subscribed);
        var maxFileCount = issubscribed?10:1;
        var images = @json($images);
        var imageArray = [];
        var urlArray = [];
        $.each(images, function (index, value){
            imageArray.push(value.image);
            urlArray.push({url: value.id+'/delete'});
        })

        var csrf = $('meta[name="csrf-token"]').attr('content');

    $("#file-1").fileinput({
        theme: 'fas',
        // showUpload: false,
        maxFileCount: maxFileCount,
        showCaption: false,
        browseClass: "btn btn-primary btn-lg",
        // fileType: "any",
        allowedFileTypes:['image'],
        allowedFileExtensions:['jpg', 'gif', 'png'],
        // previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        overwriteInitial: false,
        initialPreviewAsData: true,
        initialPreview: imageArray,
        initialPreviewConfig: urlArray,
        // initialPreviewConfig: [
        //     {url: "1/delete"},
        //     {url: "1/delete"}
        // ],
        deleteExtraData:{
            "_token":csrf
        }
    });
    $("#file-1").on("filepredelete", function(jqXHR) {
        var abort = true;
        if (confirm("Are you sure you want to delete this image?")) {
            abort = false;
        }
        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
    });
    </script>
@endpush
