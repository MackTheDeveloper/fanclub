@extends('admin.layouts.master')
<title>Add Brand | fanclub</title>

@section('content')
<script type="text/javascript">
	var language = <?php echo json_encode($language);?>;
</script>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar closed-sidebar">
    @include('admin.include.header')
	<div class="app-main">
        @include('admin.include.sidebar')
	    <div class="app-main__outer">
	        <div class="app-main__inner">
	        	<div class="app-page-title">
                    <div class="page-title-wrapper">

                    	<div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Brands</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
				                        <ol class="breadcrumb">
				                            <li class="breadcrumb-item"><a href="javascript:void(0);">Brand</a></li>
				                            <li class="active breadcrumb-item" aria-current="page"><a href="{{url(config('app.adminPrefix').'/manufacturers')}}">Brands List</a></li>
				                            <li class="active breadcrumb-item" aria-current="page">Add New Brand</li>
				                        </ol>
				                    </nav> 
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="main-card mb-3 card element-block-example">
                    <div class="card-header">
                    	{{ $formTitle }}
                    </div>
                    <div class="card-body">
                    	<form method="post" action="add" name="addManufacturer" enctype="multipart/form-data">
                    		@csrf
                    		<div class="form-row">
                    			
                    			<div class="col-md-3">
                                    <div class="position-relative form-group">
                                    	<label for="exampleEmail"> Default Language </label>
                                    </div>
                                </div>
	                            @if($page != 'anotherLanguage')
	                                <div class="col-md-9">
	                                    <div class="position-relative form-group">
	                                    	<label for="exampleEmail"> {{ $defaultLanguage }} </label>
	                                    	<input type="hidden" name="defaultLanguage" id="defaultLanguage" value="{{ $defaultLanguageId }}" readonly="true">
	                                    </div>
	                                </div>
                                @else
                                	<input type="hidden" name="brandId" id="brandId" value="{{$brandId}}">
	                                <div class="col-md-9">
	                                    <div class="position-relative form-group">
	                                    	<select class="form-control multiselect-dropdown" name="defaultLanguage" id="defaultLanguage">
	                                		</select>
	                                    </div>
	                                </div>
                                @endIf

                    			<div class="col-md-3">
                                    <div class="position-relative form-group">
                                    	<label for="exampleEmail"> Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="position-relative form-group">
                                    	<input name="brandName" id="brandName" placeholder="Brand Name" type="text" class="form-control">
                                    </div>
                                </div>

                                @if($page != 'anotherLanguage')
	                                <div class="col-md-3">
	                                    <div class="position-relative form-group">
	                                    	<label for="examplePassword"> Brand Logo</label>
	                                    </div>
	                                </div>
	                                <div class="col-md-9">
	                                    <div class="position-relative form-group">
	                                    	<input name="brandLogo" id="brandLogo" placeholder="Brand Logo" type="file" class="form-control">
	                                    </div>
	                                </div>
                                @endIf

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                    	<label for="examplePassword"> Description</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="position-relative form-group">
                                    	<textarea name="description" id="description" placeholder="Description" type="text" class="form-control ckeditor"></textarea>
                                    </div>
                                </div>

                                @if($page != 'anotherLanguage')
	                                <div class="col-md-3">
	                                    <div class="position-relative form-group">
	                                    	<label for="examplePassword"> Status <span class="text-danger">*</span></label>
	                                    </div>
	                                </div>
	                                <div class="col-md-9">
	                                    <div class="position-relative form-group">
	                                    	<select class="form-control" name="status" id="status">
	                                    		<option value="">Select Status</option>
	                                    		<option>Active</option>
	                                    		<option>InActive</option>
	                        				</select>
	                                    </div>
	                                </div>
                                @endIf
                                
                                <div class="offset-md-3 col-md-9">
                                	<button type="submit" class="btn btn-primary">Add Brand</button>
                                	<a href="{{url(config('app.adminPrefix').'/manufacturers')}}"> <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button> </a>
                                </div>

                    		</div>
                    	</form>
                    </div>
                </div>
	        </div>
	    </div>
    </div>
</div>
@push('scripts')
	<script src="{{asset('public/assets/js/manufacturer/addManufacturer.js')}}"></script>
@endpush
@endsection
