@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Product | '.config('app.name_show') : 'Add Product | '.config('app.name_show'); ?></title>
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
                                        <i class="fa pe-7s-cart"></i>
                                    </span>
                                    <span class="d-inline-block">Products</span>
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
                                                <a href="javascript:void(0);">Products</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/product/index')}}">
                                                    List</a>
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
                        <h5 class="card-title">Product INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/product/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/product/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addProductForm','autocomplete'=>'off','enctype'=>"multipart/form-data")) }}
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('category_id', 'Product Category', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::select('category_id[]', $productCategories, $model->category_id, ['class' => 'form-control multiselect-dropdown', 'multiple']); ?>
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
                                    <?php echo Form::label('user_id', 'Professional', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::select('user_id', $professionals, $model->user_id, ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('price', 'Price', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('price', $model->price, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('main_image', 'Image', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('main_image', ['id' => 'main_image', 'class' => '', 'value' => old('main_image')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.productImageDimention.width')}} X {{config('app.productImageDimention.height')}} px.</small>
                                    </div>
                                </div>
                                <?php if (isset($model->id)) { ?>
                                    <div class="col-md-2" style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{$model->main_image}}')"><img src="{{$model->main_image}}" width="50" height="50" alt="" /></a></div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('other_image', 'Other Image', ['class' => 'font-weight-bold']); ?>
                                    <div>
                                        <?php echo Form::file('other_image[]', ['id' => 'other_image', 'multiple', 'class' => '', 'value' => old('other_image')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.productOtherImageDimention.width')}} X {{config('app.productOtherImageDimention.height')}} px.</small>
                                    </div>
                                </div>
                                <?php if (isset($otherImages) && !empty($otherImages)) { ?>
                                    @foreach ($otherImages as $otherImage)
                                    <div class="col-md-2" style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{url("public/assets/images/product_other_image/" . $otherImage)}}')"><img src="{{url('public/assets/images/product_other_image/' . $otherImage)}}" width="50" height="50" alt="" /></a></div>
                                    @endforeach
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('video', 'Video', ['class' => 'font-weight-bold']); ?>
                                    <div>
                                        <?php echo Form::file('video', ['id' => 'video', 'class' => '', 'value' => old('video')]); ?>
                                    </div>
                                    @if($model->video)
                                    <video width="320" height="240" controls>
                                        <source src="{{ $model->video }}" type="video/mp4">
                                        <!-- <source src="movie.ogg" type="video/ogg"> -->
                                        Your browser does not support the video tag.
                                    </video>
                                    <br>
                                    <a href="{{ url(config('app.adminPrefix').'/product/edit/'.$model->id.'/deleteVideo') }}" class="text-danger">Delete Video</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php echo Form::label('description', 'Description', ['class' => 'font-weight-bold']); ?>
                                        {{-- <span class="text-danger">*</span> --}}
                                        <div>
                                            <?php echo Form::textarea('description', $model->description, ['class' => 'form-control ckeditor']); ?>
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
                                <button type="submit" class="btn btn-primary" id="addProductCategories"><?php echo $model->id ? 'Update' : 'Add'; ?>
                                </button>
                                <a href="{{ url(config('app.adminPrefix').'/product/index') }}">
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
    <script src="{{asset('public/assets/js/products/products.js')}}"></script>
    @endpush