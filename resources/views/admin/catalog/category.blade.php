@extends('admin.layouts.master')
<title>Category | fanclub</title>

@section('content')
<style type="text/css">
	.jstree-default-contextmenu{
		z-index: 10000;
	}
</style>
@push('styles')
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
@endpush

<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
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
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Category</span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a>
                                                    <i aria-hidden="true" class="fa fa-home"></i>
                                                </a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Category
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">                            
                            <div class="d-inline-block dropdown">
                            	<button id="categoryExport" class="btn btn-primary mb-2" >Export Category</button>
                                <!-- <button class="btn btn-square btn-primary btn-sm" id="divFilterToggle"> <i aria-hidden="true" class="fa fa-filter"></i> Filter </button> -->
                            </div>
                        </div>


                        
                    </div>
                </div>

                <div class="main-card mb-3 card">
                    <div class="card-body">
		                <div class="row">
		                    <div class="col-md-3">
		                    	<div id="jstree_demo_div"></div>
		                    </div>
		                        
		                  	<div class="col-md-9">
		                      	<div class="portlet box green categoryblock">

					               	<form class="" id="category_frm">
					               		<input type="hidden" name="operation" id="operation" value="update_node" readonly="true">
					               		<input type="hidden" name="categoryId" id="categoryId">
		                                <div class="form-row">
		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="exampleEmail" class=""> Name <span class="text-danger">*</span></label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="categoryName" id="categoryName" placeholder="Category Name" type="text" class="form-control">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- s --> Front Name</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="frontName" id="frontName" placeholder="Front Name" type="text" class="form-control">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class="">SKU Prefix <span class="text-danger">*</span> </label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="skuPrefix" id="skuPrefix" placeholder="SKU Prefix" type="text" class="form-control">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Category Slug</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="categorySlug" id="categorySlug" placeholder="Category Slug" type="text" class="form-control">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Status</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="status" id="status">
		                                        		<option>Active</option>
		                                        		<option>InActive</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Display in Top Menu</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="displayTopMenu" id="displayTopMenu">
		                                        		<option>No</option>
		                                        		<option>Yes</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Display in Home</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="displayInHome" id="displayInHome">
		                                        		<option>No</option>
		                                        		<option>Yes</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Active From</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="activeFrom" id="activeFrom" placeholder="Active From" type="text" class="form-control">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> --> Active Till</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input class="form-control input-mask-trigger" name="activeTill" id="activeTill" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" im-insert="false">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><span class="text-danger">*</span> Category Image</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<input name="categoryImage" id="categoryImage" placeholder="Category Image" type="file" class="form-control">
		                                        </div>
		                                        <div class="mb-2 mt-2">
		                                        	<img id="imageCategory">
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Meta Title</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<textarea name="metaTitle" id="metaTitle" placeholder="Meta Title" type="file" class="form-control"></textarea>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Meta Keywords</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<textarea name="metaKeywords" id="metaKeywords" placeholder="Meta Keywords" type="file" class="form-control"></textarea>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Meta Description</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<textarea name="metaDescription" id="metaDescription" placeholder="Meta Description" type="file" class="form-control"></textarea>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Is Refundable Product</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="refundable" id="refundable">
		                                        		<option value="No">No</option>
		                                        		<option value="Yes">Yes</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Is Replaceable Product</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="replaceable" id="replaceable">
		                                        		<option>No</option>
		                                        		<option>Yes</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Tag</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group">
		                                        	<select class="form-control" name="tag" id="tag">
		                                        		<option>New</option>
		                                        		<option>Hot</option>
		                            				</select>
		                                        </div>
		                                    </div>

		                                    <div class="col-md-3">
		                                        <div class="position-relative form-group">
		                                        	<label for="examplePassword" class=""><!-- <span class="text-danger">*</span> -->Shipment</label>
		                                        </div>
		                                    </div>
		                                    <div class="col-md-9">
		                                        <div class="position-relative form-group" name="shipBy" id="shipBy">
		                                        	<select class="form-control">
		                                        		<option>New</option>
		                                        		<option>Hot</option>
		                            				</select>
		                                        </div>
		                                    </div>
		                                </div>

		                                <!-- <div class="ml-auto">
		                                    <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg">Update</button>
		                                </div> -->
		                                <div class="form-group">
				                            <button type="submit" class="btn btn-primary" >Update</button>
				                        </div>
		                            </form>
					           </div>
					       </div>
			           </div>
			       </div>
			   </div>



	        </div>
	    </div>
    </div>
</div>

@push('scripts')
	<script src="{{asset('public/assets/js/catelog/category.js')}}"></script>
@endpush

<script type="text/javascript">
	$( document ).ready(function() {
		//form submit
		$('#activeFrom').datepicker();
		$('#activeTill').datepicker();

		$('#category_frm').submit(function (e) {
    		e.preventDefault();
      		if($(this).valid()){

          		$.ajaxSetup({
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    }
				});
	          	$.ajax({
	              	type: 'post',
	              	enctype: 'multipart/form-data',
	              	url: 'category/update',
	              	data: new FormData(this),
	              	processData: false,
					contentType: false,
	              	// data: {'categoryId': $('#categoryId').val(), 'operation':$('#operation').val(), 'categoryName':$('#categoryName').val(), 'frontName':$('#frontName').val(), 'skuPrefix':$('#skuPrefix').val(), 'categorySlug':$('#categorySlug').val(), 'status':$('#status').val(), 'displayTopMenu':$('#displayTopMenu').val(), 'displayInHome':$('#displayInHome').val(), 'activeFrom':$('#activeFrom').val(), 'activeTill':$('#activeTill').val(), 'categoryImage':$('#categoryImage').val(), 'metaTitle':$('#metaTitle').val(), 'metaKeywords':$('#metaKeywords').val(), 'metaDescription':$('#metaDescription').val(), 'refundable':$('#refundable').val(), 'replaceable':$('#replaceable').val(), 'tag':$('#tag').val(), 'shipBy':$('#shipBy').val()},//new FormData(this),//$('#category_frm').serialize(),//new FormData(this),
	              	// dataType: "json",
	              	beforeSend: function() {
		               	$('#loaderimage').css("display", "block");
		               	$('#loadingorverlay').css("display", "block");
	              	},
	              	success: function (response) {
          			

	                	$('#loaderimage').css("display", "none");
	                	$('#loadingorverlay').css("display", "none");
	                	if (response.msg=="success") {
	                		var input = $("#categoryImage");
	            			input.replaceWith(input.val('').clone(true));
	                   		if( response.catImage!="") {
	                     		$('.catimg').attr('src',response.catImage);
	                      		if ( response.cat_depth==1 ) {
	                          		$('.image_div').show();
	                      		} else {
	                          		$('.image_div').hide();
	                      		}
	                   		}
		                   	toastr.options.closeButton = true;
		                   	toastr.options.timeOut = 0;
		                   	toastr.success('Category has been updated successfully')
	                  		//$('#categorymsg').empty().html('<div class="alert alert-success" id="flashsuccess">Category has been updated successfully</div>').animate({ scrollTop: 0 }, "slow");

	                	} else if( response.msg=="invalid") {
	                   		var errorString = '<ul>';
	                   		$.each( response.errors, function( key, value) {
	                       		errorString += '<li>' + value + '</li>';
	                   		});
	                   		errorString += '</ul>';
	                   		$('#categorymsg').empty().html('<div class="alert alert-danger"  id="flasherror">You have some form errors.'+errorString+'</div>');
	                	}
	                	else if(response.msg=="slug_exist"){
	                  		toastr.error('Category Slug already exist.');
	                  		//$('#categorymsg').empty().html('<div class="alert alert-danger"  id="flasherror">Category Slug already exist.</div>');
	                  		return false;
	                	} else {
	                   		$('#categorymsg').empty().html('<div class="alert alert-danger"  id="flasherror">An error occur in Category updation.</div>');
	                   		return false;
	                	}
	              	}
	          	});
        		e.preventDefault();

      		}
		});

    	//Validate Form
    	$('#category_frm').validate(
    	{
        	rules: {
            	categoryName:"required",
            	skuPrefix:"required",
	            // categoryImage:{
	            //     extension: "jpeg|jpg|png|gif"
	            // }
        	},
        	messages: {
        		categoryName : 'Category Name Required',
        		skuPrefix: "Sku Prefix required"
        	}
    	});
	});

</script>

@endsection
