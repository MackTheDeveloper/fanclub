@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit  Page' : 'Add  Page'; ?></title>

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
                                    <span class="d-inline-block">Footer</span>
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
                                                <a href="javascript:void(0);">Footer</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/footer/list')}}">List</a>
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
                        <h5 class="card-title">Footer INFORMATION</h5>
                        <?php
                        if ($model->id)
                            $actionUrl = url(config('app.adminPrefix').'/footer/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/footer/store');
                        ?>
                        <form id="addCmsPageForm" class="" method="post" action="{{$actionUrl}}">
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
                                        <label for="sort_order" class="font-weight-bold">Sort Order</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="sort_order" name="sort_order" placeholder="Enter Sort Order" value="{{$model->sort_order}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Type</label>
                                        <br>
                                          <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'cms' ? '<input class="custom-control-input" type="radio" name="type" id="cms" value="cms" checked>' :  '<input class="custom-control-input" type="radio" name="type" id="cms" value="cms" checked>'; ?>
                                            <label class="custom-control-label" for="cms">CMS</label>
                                        </div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'artist' ?'<input class="custom-control-input" type="radio" name="type" id="artist" value="artist" checked>' : '<input class="custom-control-input" type="radio" name="type" id="artist" value="artist">'; ?>
                                            <label class="custom-control-label" for="artist">Artist</label>
                                        </div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'category' ?'<input class="custom-control-input" type="radio" name="type" id="category" value="category" checked>' : '<input class="custom-control-input" type="radio" name="type" id="category" value="category">'; ?>
                                            <label class="custom-control-label" for="category">Category</label>
                                        </div>
                                         <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'genre' ? '<input class="custom-control-input" type="radio" name="type" id="genre" value="genre" checked>' :  '<input class="custom-control-input" type="radio" name="type" id="genre" value="genre">'; ?>
                                            <label class="custom-control-label" for="genre">Genre</label>
                                        </div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'language' ?'<input class="custom-control-input" type="radio" name="type" id="language" value="language" checked>' : '<input class="custom-control-input" type="radio" name="type" id="language" value="language">'; ?>
                                            <label class="custom-control-label" for="language">Language</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <div class="">
                                        <select class="form-control multiselect-dropdown" id="dropdown" name="dropdown[]" data-live-search="true" multiple="true" value="{{ old('type') }}" >
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
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                    <a href="{{ url(config('app.adminPrefix').'/footer/list') }}">
                                    <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <script src="{{asset('public/assets/js/footer/footer.js')}}"></script>
    <script>
        let page_name = '<?php echo $page_name; ?>'
    </script>
@endpush
