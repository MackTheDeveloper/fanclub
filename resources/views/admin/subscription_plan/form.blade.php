@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Subscription Plans </title>
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
                                    <span class="d-inline-block">Subscription Plans</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Subscription Plans</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{ url(config('app.adminPrefix').'/subscription-plan/index') }}" style="color: grey">List</a>
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
                        <h5 class="card-title">Subscription Plans INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/subscription-plan/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/subscription-plan/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addMusicCategoryForm','autocomplete'=>'off','enctype'=>'multipart/form-data')) }}
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('subscription_name', 'Name', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('name', $model->subscription_name, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('type', 'Type', ['class' => 'font-weight-bold']); ?>
                                     <i class="fas fa-calendar-week"></i>
                                     @if ($model->type == 1)
                                     <?php echo Form::text('type',"Monthly", ['class' => 'form-control','disabled'=>true]); ?>
                                     @else
                                     <?php echo Form::text('type',"Yearly", ['class' => 'form-control','disabled'=>true]); ?>
                                     @endif
                                    <div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('duration', 'Duration', ['class' => 'font-weight-bold']); ?>
                                    <i class="fas fa-stopwatch"></i>
                                    <div>
                                        <?php echo Form::text('duration', $model->duration, ['class' => 'form-control','disabled'=>true]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('price', 'Price', ['class' => 'font-weight-bold']); ?>
                                    <i class="fas fa-dollar-sign"></i>
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
                                    <?php echo Form::label('description', 'Description', ['class' => 'font-weight-bold']); ?>
                                    <i class="fas fa-info-circle"></i>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::textarea('description', $model->description, ['class' => 'form-control','rows'=>3]); ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addHowItWorks"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/subscription-plan/index') }}">
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
        /** add  music cateogry form validation */
        $("#addMusicCategoryForm").validate({
        ignore: [], // ignore NOTHING
        rules: {
            name: {
                required: true,
            },
            price: {
                required: true,
            },
            description: {
                required: true,
            },
        },
        messages: {
            "name": {
                required: "Please enter name"
            },
            "price": {
                required: "Please enter price"
            },
            "description": {
                required: "Please enter description"
            },
        },
        errorPlacement: function(error, element) {
            if ( element.is(":radio") ) {
                error.prependTo( element.parent().parent() );
            }
            else { // This is the default behavior of the script
                error.insertAfter( element );
            }
        },
        submitHandler: function(form)
        {
            form.submit();
        }
    });
</script>
@endpush
