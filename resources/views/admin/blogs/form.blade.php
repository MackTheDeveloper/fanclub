@extends('admin.layouts.master')
<title>{{config('app.name_show')}}<?php echo $model->id ? ' | Update Song' : ' | Add Song'; ?></title>
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
                                        <i class="fa pe-7s-browser"></i>
                                    </span>
                                    <span class="d-inline-block">Blogs</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Blogs</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/songs/index')}}" style="color: grey">
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
                        <h5 class="card-title">SONG INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/songs/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/songs/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addBlogForm','autocomplete'=>'off','enctype'=>"multipart/form-data")) }}
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('blog_category_id', 'Blog Category', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::select('blog_category_id', $blogCategories, $model->blog_category_id, ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('title', 'Title', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('title', $model->title, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('image', 'Image', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('image', ['id' => 'image', 'class' => '', 'value' => old('image')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.blogImageDimention.width')}} X {{config('app.blogImageDimention.height')}} px.</small>
                                    </div>
                                    <?php if (isset($model->image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{ url("public/assets/images/blog_image/". $model->image) }}')"><img src="{{ url('public/assets/images/blog_image/'. $model->image) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('cover_image', 'Cover Image', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('cover_image', ['id' => 'cover_image', 'class' => '', 'value' => old('cover_image')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.blogBannerImageDimention.width')}} X {{config('app.blogBannerImageDimention.height')}} px.</small>
                                    </div>
                                    <?php if (isset($model->cover_image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{ url("public/assets/images/blog_cover_image/". $model->cover_image) }}')"><img src="{{ url('public/assets/images/blog_cover_image/'. $model->cover_image) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('short_description', 'Short Description', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::textarea('short_description', $model->short_description, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('long_description', 'Long Description', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::textarea('long_description', $model->long_description, ['class' => 'form-control ckeditor']); ?>
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

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addBlogCategories"><?php echo $model->id ? 'Update' : 'Add'; ?>
                            </button>
                            <a href="{{ url(config('app.adminPrefix').'/songs/index') }}">
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
    let checkRecord = <?php echo $model->id ? 0 : 1; ?>;
</script>
<script src="{{asset('public/assets/js/homepagebanner/homepagebanner.js')}}"></script>
@endpush
