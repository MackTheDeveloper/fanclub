@extends('admin.layouts.master')
@php
use App\Models\GlobalSettings;
@endphp
@section('title','Edit Location Groups')
@section('content')
    <style type="text/css">
        .inputGroup input{
            width: 91%;
            display: inline-block;
        }
        .inputGroup a{
            width: 8%;
            display: inline-block;
        }
    </style>
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
                                    <span class="d-inline-block">Location Groups</span>
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
                                                <a href="javascript:void(0)">Location Groups</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/locationGroups/list')}}">List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Edit
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>                            
                        </div>                          
                    </div>
                </div>                                                        
                <form id="addNewrecord" method="post" action="{{url(config('app.adminPrefix').'/locationGroups/update')}}" enctype="multipart/form-data">
                @csrf
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            @if(Session::has('msg'))                     
                                <div class="alert {{(Session::get('alert-class') == true) ? 'alert-success' : 'alert-danger'}} alert-dismissible fade show" role="alert">
                                    {{ Session::get('msg') }}
                                    <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif  
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="group_name">Group Name<span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter first name" value="{{$model->group_name}}"/>                                        
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">City <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="{{ $model->city }}" />                                        
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="search_area">Search Area <span class="text-danger">*</span></label>
                                            <div class="inputGroup">
                                                <input type="text" class="form-control" id="autocomplete_search" name="search_area" placeholder="Search area" value="{{ old('search_area') }}" />
                                                <a href="javascript:void(0)" class="btn btn-success add_area"><i class="fa fa-plus"></i></a>                              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="areas">Areas <a href="#" rel="tooltip" title="Please add areas by comma separated">(i)</a> <span class="text-danger">*</span></label>
                                            <div>
                                                <textarea class="form-control" name="areas" id="areas">{{ $model->areas }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Is Active?<span class="text-danger">*</span></label>
                                            <div>
                                                <select class="form-control" name="status">
                                                    <option value="1" {{ $model->status == 1 ? 'selected' : '' }} >Yes</option>
                                                    <option value="0" {{ $model->status == 0 ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary" name="update_role" value="update_role">Update</button>
                                    <a href="{{url(config('app.adminPrefix').'/locationGroups/list')}}"><button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button></a>
                                </div>
                                <input type="hidden" name="id" value="{{$model->id}}">
                            </div>
                    </div>
                </form>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ globalSettings::getSingleSettingVal('google_api_key') }}&libraries=places&callback=initialize" async></script>
<script>
$(document).ready(function(){               
    $("[rel='tooltip']").tooltip();
    $("#addNewrecord").validate( {
        rules: {
            group_name: "required",
            areas: "required",
            city: "required"
        },
        messages: {
            group_name: "Please enter group name",
            areas: "Please enter area",
            city: "Please enter city",
        },
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
    } );      
})

function initialize() {
    var area = '';
    var input = document.getElementById('autocomplete_search');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function () {
        // show_page_block_loader();

        var place = autocomplete.getPlace();
        // place variable will have all the information you are looking for.
    
        var placeId = place.place_id;
        // console.log(place);
        for (var i = 0; i < place.address_components.length; i++) {
            for (var j = 0; j < place.address_components[i].types.length; j++) {
                if (place.address_components[i].types[j] == "sublocality_level_1") {
                    // $('#autocomplete_search').val(place.address_components[i].long_name);
                    area = place.address_components[i].long_name;
                }
            }
        }
        // hide_page_block_loader();
        // searchArea(area);
        $('#autocomplete_search').val(area);
    });
}

$(document).on('click','.add_area',function(){
    var newArea = $(this).closest('.inputGroup').find('#autocomplete_search').val();
    addNewArea(newArea)
})
function addNewArea(areaName){
    if(areaName){
        var existingArea = $('#areas').val();
        if(existingArea){
            existingArea = existingArea.split(',');
        }else{
            existingArea =[];
        }

        if(existingArea){
            existingArea = existingArea.map(Function.prototype.call, String.prototype.trim);
        }
        
        if(!existingArea.includes(areaName)){
            existingArea.push(areaName);
        }
        if(existingArea){
            existingArea = existingArea.join(', ');
        }else{
            existingArea = '';
        }
        $('#areas').val(existingArea);
        $('#autocomplete_search').val('');
    }
}
</script>
@endpush
