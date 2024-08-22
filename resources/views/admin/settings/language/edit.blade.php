@extends('admin.layouts.master')
<title>Edit Global Language | fanclub</title>

@section('content')
<div class="app-container body-tabs-shadow fixed-header fixed-sidebar app-theme-white closed-sidebar">
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
                                    <span class="d-inline-block">Language</span>
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
                                                <a href="javascript:void(0);">Settings</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/language/list')}}">Language</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Edit Language  
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
                        <h5 class="card-title">Language Information</h5>                          
                        <form class="col-md-6" method="post" action="{{url(config('app.adminPrefix').'/language/update')}}">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="glb_lang_id" id="glb_lang_id" value="{{$language->id}}" />
                            <label for="country_selector">Select Langauge<span style="color:red">*</span></label>
                    
                            <div class="form-group">
                                <select name="language_selector" id="language_selector" class="multiselect-dropdown form-control">
                                    @foreach($w_languages as $w_language)
                                        <option value="{{$w_language->sortcode}}" {{($w_language->id == $language->language_id) ? 'selected' : ''}}>{{$w_language->lang_name}}</option>    
                                    @endforeach                                    
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="update_language" id="update_language" value="update_language">Update Language</button>
                                <a href="{{url(config('app.adminPrefix').'/language/list')}}"><button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button></a>
                            </div>
                        </form>                
                    </div>
                </div>                                              
            </div>
            @include('admin.include.footer')
        </div>
    </div>    
</div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')

<!-- <script>
$(document).ready(function(){
    
    $('#update_language').click(function(){        
        var sortcode = $('#language_selector').val();
        var glb_lang_id = $('#glb_lang_id').val();     
        $.ajax({
            url: "{{url(config('app.adminPrefix').'/language/update')}}",
            method: "POST",
            data: {
                "_token": $('#_token').val(),
                sortcode: sortcode,
                glb_lang_id: glb_lang_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    // window.location.href = window.location.href + '/../../list';
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success("Language updated successfully!"); 
                    setTimeout(function(){ 
                        toastr.clear();
                    }, 5000);
                }
                else
                {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg); 
                    setTimeout(function(){ 
                        toastr.clear();
                    }, 5000);  
                }
            }
        })
    })
})
</script> -->
@endpush
