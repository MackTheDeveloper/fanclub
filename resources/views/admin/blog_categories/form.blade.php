@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Blog Category | '.config('app.name_show') : 'Add Blog Category | '.config('app.name_show'); ?></title>
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
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <span class="d-inline-block">Songs Categories</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Song Categories</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/songs-category/index')}}" style="color: grey">
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
                        <h5 class="card-title">Blog Category INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/songs-category/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/songs-category/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addBlogCategoryForm','autocomplete'=>'off')) }}
                        @csrf
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
                            <button type="submit" class="btn btn-primary" id="addBlogCategories"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/songs-category/index') }}">
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
<script src="{{asset('public/assets/js/blog_categories/blog_categories.js')}}"></script>
@endpush
