@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Music Language | ' . config('app.name_show') : 'Add Music Language | ' . config('app.name_show'); ?></title>
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
                                    <i class="fa pe-7s-music"></i>
                                </span>
                                <span class="d-inline-block">Music Languages</span>
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
                                            <a href="javascript:void(0);" style="color: grey">Music Languages</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{url(config('app.adminPrefix').'/music-languages/index')}}" style="color: grey">
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
                    <h5 class="card-title">Music Language INFORMATION</h5>
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
                        $actionUrl = url(config('app.adminPrefix') . '/music-languages/update', $model->id);
                    else
                        $actionUrl = url(config('app.adminPrefix') . '/music-languages/store');
                    ?>
                    
                    <form id="addMusicLanguageForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
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
                                    <label for="seo_title" class="font-weight-bold">Sort Order</label>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <input type="number" class="form-control" id="sortOrder" name="sortOrder" placeholder="Enter Sort Order" value="{{$model->sort_order}}" />
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
                                            <option value="1" {{$model->status == '1' ? 'selected' : ''}}>Active</option>
                                            <option value="0" {{$model->status == '0' ? 'selected' : ''}}>Inactive</option>
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
                                        <small class="form-text text-muted">Image size should be {{config('app.musicLanguageIconDimension.width')}} X {{config('app.musicLanguageIconDimension.height')}} px.</small>
                                    </div>
                                    <?php if (isset($model->image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{ $model->image }}')"><img src="{{ $model->image }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/music-languages/index') }}">
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
<script src="{{asset('public/assets/js/music_management/music_languages.js')}}"></script>
@endpush