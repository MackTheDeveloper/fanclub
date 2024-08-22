@extends('admin.layouts.master')
<title>{{$model->id ? 'Edit Group | ' . config('app.name_show') : 'Add Group | ' . config('app.name_show')}}</title>
@section('content')
<script type="text/javascript">
    var baseUrl = "{{$baseUrl}}";
    var grpId = "{{$model->id}}";
    var grpType = "{{$model->type}}";
</script>
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
                                    <i class="fa pe-7s-home"></i>
                                </span>
                                <span class="d-inline-block">Dynamic Group</span>
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
                                            <a href="javascript:void(0);" style="color: grey">Dynamic Group</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{url(config('app.adminPrefix').'/dynamic-groups/index')}}" style="color: grey">
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
                    @if($model->id && ($model->view_all == '0' || $model->view_all == ''))
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a data-toggle="tab" href="#details_form" class="active nav-link">Group Details</a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="tab" href="#data_form" class="nav-link">Group Data</a>
                        </li>
                    </ul>
                    @else
                    <h5 class="card-title">GROUP DETAILS</h5>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @php
                    if ($model->id){
                    $actionUrl = url(config('app.adminPrefix') . '/dynamic-groups/update', $model->id);
                    }else{
                    $actionUrl = url(config('app.adminPrefix') . '/dynamic-groups/store');
                    }
                    @endphp
                    <div class="tab-content">
                        <div class="tab-pane active" id="details_form" role="tabpanel">
                            {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addGroupForm','enctype'=>"multipart/form-data",'autocomplete'=>'off')) }}
                            @csrf
                            @if($model->id)
                            @if($model->view_all == '0')
                            <h5 class="card-title">GROUP DETAILS</h5>
                            @endif
                            <input type="hidden" name="serachType" value="{{$model->type}}" id="serachType" />
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('name', 'Name', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php echo Form::text('name', $model->name, ['class' => 'form-control']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('slug', 'Slug', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php echo Form::text('slug', $model->slug, ['class' => 'form-control']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="position-relative form-group">
                                        <label for="type"><strong>Type</strong></label>
                                        <span class="text-danger">*</span>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="type" name="type" class="custom-control-input" value="1" <?php echo $model->type == '1' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="type">Artists</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="type2" name="type" class="custom-control-input" value="2" <?php echo $model->type == '2' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="type2">Songs</label>
                                                </div>
                                                {{-- <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="type3" name="type" class="custom-control-input" value="3" <?php echo $model->type == '3' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="type3">Genres</label>
                                                </div> --}}
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="type4" name="type" class="custom-control-input" value="4" <?php echo $model->type == '4' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="type4">Categories</label>
                                                </div>
                                                {{-- <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="type5" name="type" class="custom-control-input" value="5" <?php echo $model->type == '5' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="type5">Languages</label>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="position-relative form-group">
                                        <label for="type"><strong>Image Shape</strong></label>
                                        <span class="text-danger">*</span>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="image_shape" name="image_shape" class="custom-control-input" value="1" <?php echo $model->image_shape == '1' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="image_shape">Square</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="image_shape2" name="image_shape" class="custom-control-input" value="2" <?php echo $model->image_shape == '2' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="image_shape2">Circle</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="image_shape3" name="image_shape" class="custom-control-input" value="3" <?php echo $model->image_shape == '3' ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="image_shape3">Rectangle</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 hideShowViewAll {{(!in_array($model->type,['1','2']))?'d-none':''}}">
                                    <div class="position-relative form-group">
                                        <label for="type"><strong>Group Data</strong></label>
                                        <span class="text-danger">*</span>
                                        <div class="position-relative form-group">
                                            <div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="view_all" name="view_all" class="custom-control-input" value="0" {{($model->view_all == '0')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all">Selected</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline">
                                                    <input type="radio" id="view_all2" name="view_all" class="custom-control-input" value="1" {{($model->view_all == '1')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all2">View All</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline type-for-songs {{$model->type == 2?'':'d-none'}}">
                                                    <input type="radio" id="view_all3" name="view_all" class="custom-control-input" value="2" {{($model->view_all == '2')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all3">Latest Performances</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline type-for-songs {{$model->type == 2?'':'d-none'}}">
                                                    <input type="radio" id="view_all4" name="view_all" class="custom-control-input" value="3" {{($model->view_all == '3')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all4">Trending now</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline type-for-songs {{$model->type == 2?'':'d-none'}}">
                                                    <input type="radio" id="view_all5" name="view_all" class="custom-control-input" value="4" {{($model->view_all == '4')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all5">Top Songs</label>
                                                </div>
                                                <div class="custom-radio custom-control custom-control-inline type-for-artist {{$model->type == 1?'':'d-none'}}">
                                                    <input type="radio" id="view_all6" name="view_all" class="custom-control-input" value="5" {{($model->view_all == '5')?'checked':''}}>
                                                    <label class="custom-control-label" for="view_all6">New Artists</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 hideShowConfigure {{(in_array($model->type,['1','2']) && in_array($model->view_all,['2','3','4', '5']))?'':'d-none'}}">
                                    <div class="form-group">
                                        <?php echo Form::label('allow_max', 'Configure limit', ['class' => 'font-weight-bold']); ?>
                                        <div>
                                            <?php echo Form::text('allow_max', $model->allow_max, ['class' => 'form-control']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="font-weight-bold">Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1" <?php echo $model->status == '1' ? 'selected' : ''; ?>>Active</option>
                                                <option value="0" <?php echo $model->status == '0' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="card-title">SEO INFORMATION</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="seo_title" class="font-weight-bold">SEO Title</label>
                                        <div>
                                            <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="Enter SEO Title" value="{{$model->seo_title}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_meta_keyword" class="font-weight-bold">SEO Meta Keyword</label>
                                        <div>
                                            {{Form::textarea('seo_meta_keyword', $model->seo_meta_keyword, ['class' => 'form-control', 'rows' => '3'])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_description" class="font-weight-bold">SEO Description</label>
                                        <div>
                                            {{Form::textarea('seo_description', $model->seo_description, ['class' => 'form-control', 'rows' => '3'])}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="addArtist"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                <a href="{{ url(config('app.adminPrefix').'/dynamic-groups/index') }}">
                                    <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                                </a>
                            </div>
                            {{ Form::close() }}
                        </div>
                        <div class="tab-pane" id="data_form" role="tabpanel">
                            <h5 class="card-title">GROUP DATA</h5>
                            @include('admin.dynamic_groups.groupdetails')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.include.footer')
    </div>
</div>
@endsection

@push('scripts')
<script src="{{asset('public/assets/js/dynamic_groups/dynamicgroups_edit.js')}}"></script>
@endpush