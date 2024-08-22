@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit  | '.config('app.name_show') : 'Add  | '.config('app.name_show'); ?></title>
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
                            <i class="active_icon metismenu-icon pe-7s-home"></i>
                                    </span>
                                    <span class="d-inline-block">Home Page Banner</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Home Page Banner</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/homepagebanner/index')}}" style="color: grey">
                                                    List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page" style="color: slategray">
                                                <?php echo $model->id ? 'Edit' : 'Add'; ?>
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
                        <h5 class="card-title">Home Page Banner INFORMATION</h5>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <?php
                        if ($model->id)
                            $actionUrl = url(config('app.adminPrefix').'/homepagebanner/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/homepagebanner/store');
                        ?>
                        <form id="addMusicGenreForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
                            @csrf
                            <input type="hidden" name="submitMode" value="{{$model->id?'edit':'create'}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Name</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$model->name}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sortOrder" class="font-weight-bold">Sort Order</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="number" class="form-control" id="sortOrder" name="sortOrder" placeholder="Enter Sort Order"  value="{{$model->sortOrder}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="position-relative form-group">
                                        <label for="type"><strong></strong></label>
                                        <div class="position-relative form-group">
                                            <div>
                                                @foreach ($types as $key=>$item)
                                                    <div class="custom-radio custom-control custom-control-inline">
                                                        <input type="radio" id="demo{{$key}}" name="type_value" class="custom-control-input bannerUrlType" value="{{$key}}" {{($model->type==$key)?"checked":""}}>
                                                        <label class="custom-control-label" for="demo{{$key}}">{{$item}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                         <div class="">
                                        <select class="form-control multiselect-dropdown" id="dropdown" name="type_id"  value="{{ old('type_id') }}" >
                                        </select>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                            <select name="is_active" id="is_active" class="form-control">
                                                <option value="1" {{$model->is_active == '1' ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$model->is_active == '0' ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="font-weight-bold">Icon</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input name="image" id="image" type="file" class="form-control-file" value="{{old('image')}}">
                                            <small class="form-text text-muted">Image size should be {{config('app.homepageBannerDimentions.width')}} X {{config('app.homepageBannerDimentions.height')}} px.</small>
                                        </div>
                                        <?php if (isset($model->image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{ $model->image }}')"><img src="{{ $model->image }}" width="50" height="50" alt="" /></a></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                <a href="{{ url(config('app.adminPrefix').'/homepagebanner/index') }}">
                                    <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection

@push('scripts')
<script>
var modalType = "{{$model->type}}"
if (modalType) {
    ajaxData();
}

$(function(){
  $('input[type="radio"]').click(function(){
    ajaxData();
  });
});

function ajaxData(){
    // bannerUrlType
    let val = $('.bannerUrlType:checked').val();
    let submitMode = $('input[name="submitMode"]').val(); // add or edit
    let selectedItem = "{{$model->type_id}}"; // add or edit
    $.ajax({
        type:'POST',
        url:(submitMode == "create")?'typeChange':'../typeChange',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {type: val},
        dataType : 'json',
        success:function(result)
        {
            console.log(result);
            $('#dropdown').empty();
            $.each(result.data,function(key,value)
            {
                var selected = (selectedItem==value.id)?'selected="selected"':''
                $("#dropdown").append('<option value="'+value.id+'" '+selected+' >'+value.name+'</option>');
                // if(jQuery.inArray(value.id.toString(), result.selected) !== -1){
                //     $("#dropdown").append('<option selected="selected" value="'+value.id+'">'+value.name+'</option>');
                // }
                // else
                // {
                //     $("#dropdown").append('<option value="'+value.id+'">'+value.name+'</option>');
                // }
            });
        }
    });
}
    // var val = $("input[type='radio']:checked").val();
    // var url = window.location.href;
    // var id = url.substring(url.lastIndexOf('/') + 1);

    // if(val && val!=0)
    // {
    //     $.ajax({
    //         type:'POST',
    //             url:"{{route('existData')}}",
    //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //             data: {'type': val,'id':id},
    //             dataType : 'json',
    //             success:function(result)
    //             {
    //                 $("#dropdown").append('<option selected="selected">'+result.data+'</option>');
    //             }
    //     });
    // }

    </script>

    <script src="{{asset('public/assets/js/homepagebanner/homepagebanner.js')}}"></script>
@endpush
