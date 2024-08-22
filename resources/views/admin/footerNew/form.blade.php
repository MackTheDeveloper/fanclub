@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Footer| '.config('app.name_show') : 'Add Footer| '.config('app.name_show'); ?></title>
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
                                    <i class="active_icon metismenu-icon fa fa-question-circle"></i>
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
                                                <a href="javascript:void(0);" style="color: grey">Footer</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/footer-link/index')}}" style="color: grey">
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
                        <h5 class="card-title">Footer INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/footer-link/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/footer-link/create');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addMusicCategoryForm','autocomplete'=>'off','enctype'=>'multipart/form-data')) }}
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
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" placeholder="Enter Sort Order" value="{{$model->sort_order}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Type</label>
                                        <span class="text-danger">*</span>
                                        <br>
                                        @foreach ($types as $key=>$item)
                                            <div class="custom-radio custom-control custom-control-inline">
                                                <input class="custom-control-input" type="radio" name="type" id="{{$key}}" value="{{$key}}" {{$model->type==$key?"checked":""}}>
                                                <label class="custom-control-label" for="{{$key}}">{{$item}}</label>
                                            </div>  
                                        @endforeach
                                     </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                         <div class="">
                                        <select class="form-control multiselect-dropdown" required id="dropdown" name="dropdown[]" data-live-search="true" multiple="true" value="{{ old('type') }}" >
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
                            </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addHowItWorks"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/footer-link/index') }}">
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
<script src="{{asset('public/assets/js/footer/footerNew.js')}}"></script>
<script>
</script>

@endpush
