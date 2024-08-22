@extends('admin.layouts.master')
@section('title','Edit Slot Allocations')
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
                                    <span class="d-inline-block">Slot Allocations</span>
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
                                                <a href="javascript:void(0)">Slot Allocations</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/slotAllocations/list')}}">List</a>
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
                <form id="addNewrecord" method="post" action="{{url(config('app.adminPrefix').'/slotAllocations/update')}}" enctype="multipart/form-data">
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
                                            <?php echo Form::label('location_group_id', 'Location Group', ['class' => 'font-weight-bold']); ?>
                                            <span class="text-danger">*</span>
                                            <div>
                                                <?php
                                                $location_group_id = $model->location_group_id; 
                                                echo Form::select('location_group_id', $locationGroups, $location_group_id, ['class' => 'form-control multiselect-dropdown']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sort_order">Sort Order <span class="text-danger">*</span></label>
                                            <div>
                                                <input type="text" name="sort_order" class="form-control" id="sort_order" value="{{$model->sort_order}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                        <h5 class="card-title">Ad and Location Hero Selection</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('add_hero', 'Location Hero', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $ad_hero = $model->ad_hero; 
                                            echo Form::select('ad_hero', $professionals, $ad_hero, ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('ad_hero_start_date', 'Location Hero Start Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $ad_hero_start_date = $model->ad_hero_start_date;
                                            echo Form::text('ad_hero_start_date',$ad_hero_start_date,['class'=> 'date form-control','placeholder'=>'Start Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('ad_hero_end_date', 'Location Hero End Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $ad_hero_end_date = $model->ad_hero_end_date;
                                            echo Form::text('ad_hero_end_date',$ad_hero_end_date,['class'=> 'date form-control','placeholder'=>'End Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('ad_hero_cost', 'Location Hero Cost', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $ad_hero_cost = $model->ad_hero_cost;
                                            echo Form::text('ad_hero_cost',$ad_hero_cost,['class'=> ' form-control','placeholder'=>'Cost']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_1', 'Ad Hero 1', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_1 = $model->location_hero_1; 
                                            echo Form::select('location_hero_1', $professionals, $location_hero_1, ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_1_start_date', 'Ad Hero 1 Start Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_1_start_date = $model->location_hero_1_start_date;
                                            echo Form::text('location_hero_1_start_date',$location_hero_1_start_date,['class'=> 'date form-control','placeholder'=>'Start Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_1_end_date', 'Ad Hero 1 End Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_1_end_date = $model->location_hero_1_end_date;
                                            echo Form::text('location_hero_1_end_date',$location_hero_1_end_date,['class'=> 'date form-control','placeholder'=>'End Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_1_cost', 'Ad Hero 1 Cost', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_1_cost = $model->location_hero_1_cost;
                                            echo Form::text('location_hero_1_cost',$location_hero_1_cost,['class'=> ' form-control','placeholder'=>'Cost']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_2', 'Ad Hero 2', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_2 = $model->location_hero_2; 
                                            echo Form::select('location_hero_2', $professionals, $location_hero_2, ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_2_start_date', 'Ad Hero 2 Start Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_2_start_date = $model->location_hero_2_start_date;
                                            echo Form::text('location_hero_2_start_date',$location_hero_2_start_date,['class'=> 'date form-control','placeholder'=>'Start Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_2_end_date', 'Ad Hero 2 End Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_2_end_date = $model->location_hero_2_end_date;
                                            echo Form::text('location_hero_2_end_date',$location_hero_2_end_date,['class'=> 'date form-control','placeholder'=>'End Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_2_cost', 'Ad Hero 2 Cost', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_2_cost = $model->location_hero_2_cost;
                                            echo Form::text('location_hero_2_cost',$location_hero_2_cost,['class'=> ' form-control','placeholder'=>'Cost']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_3', 'Ad Hero 3', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_3 = $model->location_hero_3; 
                                            echo Form::select('location_hero_3', $professionals, $location_hero_3, ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_3_start_date', 'Ad Hero 3 Start Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_3_start_date = $model->location_hero_3_start_date;
                                            echo Form::text('location_hero_3_start_date',$location_hero_3_start_date,['class'=> 'date form-control','placeholder'=>'Start Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_3_end_date', 'Ad Hero 3 End Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_3_end_date = $model->location_hero_3_end_date;
                                            echo Form::text('location_hero_3_end_date',$location_hero_3_end_date,['class'=> 'date form-control','placeholder'=>'End Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_3_cost', 'Ad Hero 3 Cost', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_3_cost = $model->location_hero_3_cost;
                                            echo Form::text('location_hero_3_cost',$location_hero_3_cost,['class'=> ' form-control','placeholder'=>'Cost']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_4', 'Ad Hero 4', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_4 = $model->location_hero_4;
                                            echo Form::select('location_hero_4', $professionals, $location_hero_4, ['class' => 'form-control multiselect-dropdown','placeholder' => 'Select ...']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_4_start_date', 'Ad Hero 4 Start Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_4_start_date = $model->location_hero_4_start_date;
                                            echo Form::text('location_hero_4_start_date',$location_hero_4_start_date,['class'=> 'date form-control','placeholder'=>'Start Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_4_end_date', 'Ad Hero 4 End Date', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_4_end_date = $model->location_hero_4_end_date;
                                            echo Form::text('location_hero_4_end_date',$location_hero_4_end_date,['class'=> 'date form-control','placeholder'=>'End Date']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php echo Form::label('location_hero_4_cost', 'Ad Hero 4 Cost', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php
                                            $location_hero_4_cost = $model->location_hero_4_cost;
                                            echo Form::text('location_hero_4_cost',$location_hero_4_cost,['class'=> ' form-control','placeholder'=>'Cost']); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="main-card mb-3 card d-none">
                        <div class="card-body">
                        <h5 class="card-title">Category Hero Selection</h5>
                            @include('admin.slot_allocation.category_hero_selection')
                        </div>
                    </div>
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary" name="update_role" value="update_role">Update</button>
                                    <a href="{{url(config('app.adminPrefix').'/slotAllocations/list')}}"><button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button></a>
                            </div>
                            <input type="hidden" name="id" value="{{$model->id}}">
                        </div>
                    </div>
                </form>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')
<script>
$(document).ready(function(){               
    $("#addNewrecord").validate( {
        rules: {
            location_group_id: "required",
            sort_order: "required",            
            ad_hero: {
                // required:true,
                required:dependsCheck
            },
            ad_hero_start_date: {
                // required:true,
                required:dependsCheck
            },
            ad_hero_end_date: {
                // required:true,
                required:dependsCheck
            },
            ad_hero_cost: {
                // required:true,
                required:dependsCheck
            },
            location_hero_1: {
                // required:true,
                required:dependsCheck
            },
            location_hero_1_start_date: {
                // required:true,
                required:dependsCheck
            },
            location_hero_1_end_date: {
                // required:true,
                required:dependsCheck
            },
            location_hero_1_cost: {
                // required:true,
                required:dependsCheck
            },
            location_hero_2: {
                // required:true,
                required:dependsCheck
            },
            location_hero_2_start_date: {
                // required:true,
                required:dependsCheck
            },
            location_hero_2_end_date: {
                // required:true,
                required:dependsCheck
            },
            location_hero_2_cost: {
                // required:true,
                required:dependsCheck
            },       
            location_hero_3: {
                required:dependsCheck
                // depends:dependsCheck
            },       
            location_hero_3_start_date: {
                required:dependsCheck
                // depends:dependsCheck
            },
            location_hero_3_end_date: {
                required:dependsCheck
                // depends:dependsCheck
            },
            location_hero_3_cost: {
                required:dependsCheck
                // depends:dependsCheck
            },
            location_hero_4: {
                required:dependsCheck
                // depends:dependsCheck
            },        
            location_hero_4_start_date: {
                required:dependsCheck
                // depends:dependsCheck
            },
            location_hero_4_end_date: {
                required:dependsCheck
                // depends:dependsCheck
            },
            location_hero_4_cost: {
                required:dependsCheck
                // depends:dependsCheck
            },
        },
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else if ( element.hasClass('multiselect-dropdown') ) {
                error.insertAfter( element.next( "span" ) );
            } else {
                error.insertAfter( element );
            }
        },
    } );     
})

const dependsCheck = function(element){
    var elementName = element.name
    if(elementName.indexOf('ad_hero') != -1){
        var main = $('select[name="ad_hero"]').val();
        var start_date = $('input[name="ad_hero_start_date"]').val();
        var end_date = $('input[name="ad_hero_end_date"]').val();
        var cost = $('input[name="ad_hero_cost"]').val();
        if(main || start_date || end_date || cost){
            // console.log(main , start_date , end_date , cost)
            // console.log('ad_hero required')
            return true;
        }else{
            // console.log('ad_hero not required')
            return false;
        }
    }
    if(elementName.indexOf('location_hero_1') != -1){
        var main = $('select[name="location_hero_1"]').val();
        var start_date = $('input[name="location_hero_1_start_date"]').val();
        var end_date = $('input[name="location_hero_1_end_date"]').val();
        var cost = $('input[name="location_hero_1_cost"]').val();
        if(main || start_date || end_date || cost){
            // console.log(main , start_date , end_date , cost)
            // console.log('location_hero_1 required')
            return true;
        }else{
            // console.log('location_hero_1 not required')
            return false;
        }
    }
    if(elementName.indexOf('location_hero_2') != -1){
        var main = $('select[name="location_hero_2"]').val();
        var start_date = $('input[name="location_hero_2_start_date"]').val();
        var end_date = $('input[name="location_hero_2_end_date"]').val();
        var cost = $('input[name="location_hero_2_cost"]').val();
        if(main || start_date || end_date || cost){
            // console.log(main , start_date , end_date , cost)
            // console.log('location_hero_2 required')
            return true;
        }else{
            // console.log('location_hero_2 not required')
            return false;
        }
    }
    if(elementName.indexOf('location_hero_3') != -1){
        var main = $('select[name="location_hero_3"]').val();
        var start_date = $('input[name="location_hero_3_start_date"]').val();
        var end_date = $('input[name="location_hero_3_end_date"]').val();
        var cost = $('input[name="location_hero_3_cost"]').val();
        if(main || start_date || end_date || cost){
            // console.log(main , start_date , end_date , cost)
            // console.log('location_hero_3 required')
            return true;
        }else{
            // console.log('location_hero_3 not required')
            return false;
        }
    }
    if(elementName.indexOf('location_hero_4') != -1){
        var main = $('select[name="location_hero_4"]').val();
        var start_date = $('input[name="location_hero_4_start_date"]').val();
        var end_date = $('input[name="location_hero_4_end_date"]').val();
        var cost = $('input[name="location_hero_4_cost"]').val();
        if(main || start_date || end_date || cost){
            // console.log(main , start_date , end_date , cost)
            // console.log('location_hero_4 required')
            return true;
        }else{
            // console.log('location_hero_4 not required')
            return false;
        }
    }
};
</script>
<script type="text/javascript">
    $('.date').datepicker({  
       format: 'YYYY-MM-DD'
     });  
</script>
@endpush
