@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit CMS Page' : 'Add CMS Page'; ?></title>

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
                                        <i class="fa pe-7s-global"></i>
                                    </span>
                                    <span class="d-inline-block">CMS Pages</span>
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
                                                <a href="javascript:void(0);">CMS Pages</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/cms-page/list')}}">List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
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
                        <h5 class="card-title">CMS Page INFORMATION</h5>
                        <?php
                        if ($model->id)
                            $actionUrl = url(config('app.adminPrefix').'/cms-page/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/cms-page/store');
                        ?>
                        <form id="addCmsPageForm" class="" method="post" action="{{$actionUrl}}">
                            @csrf
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
                                        <label for="slug" class="font-weight-bold">Slug</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter Slug" value="{{$model->slug}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_title" class="font-weight-bold">SEO Title</label>
                                        <div>
                                            <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="Enter Seo Title" value="{{$model->seo_title}}" />
                                        </div>
                                    </div>
                                </div>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_description" class="font-weight-bold">SEO Description</label>
                                        <div>
                                            <?php echo Form::textarea('seo_description', $model->seo_description, ['class' => 'form-control', 'rows' => '4']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_meta_keyword" class="font-weight-bold">SEO Meta Keyword</label>
                                        <div>
                                            <?php echo Form::textarea('seo_meta_keyword', $model->seo_meta_keyword, ['class' => 'form-control', 'rows' => '4']); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="content" class="font-weight-bold">Content
                                        </label>
                                        <div>
                                            <textarea name="content" id="content" type="text" class="form-control ckeditor">{{$model->content}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                <a href="{{ url(config('app.adminPrefix').'/cms-page/list') }}">
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
    <script src="{{asset('public/assets/js/cms-page/cms-page.js')}}"></script>
    <script>
        let page_name = '<?php echo $page_name; ?>'
        var editor = CKEDITOR.replace( 'content', {
            filebrowserUploadUrl: "{{route('ckeditor.upload_email_image', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form',
            allowedContent: true
        } );
    </script>
@endpush
