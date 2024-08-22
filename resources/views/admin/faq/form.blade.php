@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit  | ' . config('app.name_show') : 'Add  | ' . config('app.name_show'); ?></title>
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
                                <span class="d-inline-block">FAQ</span>
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
                                            <a href="javascript:void(0);" style="color: grey">FAQ </a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{url(config('app.adminPrefix').'/faq/index')}}" style="color: grey">
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
                    <h5 class="card-title">FAQ INFORMATION</h5>
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
                        $actionUrl = url(config('app.adminPrefix') . '/faq/update', $model->id);
                    else
                        $actionUrl = url(config('app.adminPrefix') . '/faq/store');
                    ?>

                    <form id="addMusicGenreForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('type', 'Type', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'fan'  ? '<input class="custom-control-input" type="radio" name="type" id="fan" value="fan" checked>' : '<input class="custom-control-input" type="radio" name="type" id="fan" value="fan">'; ?>
                                            <label class="custom-control-label" for="fan">Fan</label>
                                        </div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                            <?php echo $model->type == 'artist'  ? '<input class="custom-control-input" type="radio" name="type" id="artist" value="artist" checked>' : '<input class="custom-control-input" type="radio" name="type" id="artist" value="artist">'; ?>
                                            <label class="custom-control-label" for="artist">Artist</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Tag Name</label>
                                    <div>
                                        <select name="dropdown[]" id="dropdown" class="form-control multiselect-dropdown" data-live-search="true" multiple>
                                            @foreach($tags as $tag)
                                            <option value="{{$tag->id}}">{{$tag->tag_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="question" class="font-weight-bold">Question
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="question" name="question" rows="3" placeholder="Enter Question">{{$model->question}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="answer">Answer
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div>
                                        <textarea class="form-control" id="answer" name="answer" rows="3" placeholder="Enter Answer">{{$model->answer}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active" class="font-weight-bold">Status
                                    </label>
                                    <div>
                                        <select name="is_active" id="is_active" class="form-control">
                                            <option value="1" {{$model->status == '1' ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{$model->status == '0' ? 'selected' : ''}}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/faq/index') }}">
                                <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="{{asset('public/assets/js/settings/faqs.js')}}"></script>
    @endpush