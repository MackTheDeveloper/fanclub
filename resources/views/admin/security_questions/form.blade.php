@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Security Questions | '.config('app.name_show') : 'Add Security Questions | '.config('app.name_show'); ?></title>
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
                                    <i class="active_icon fa pe-7s-global"></i>
                                    </span>
                                    <span class="d-inline-block">Security Questions</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Security Questions</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/security-questions/index')}}" style="color: grey">
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
                        <h5 class="card-title">security questions INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/security-questions/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/security-questions/store');
                        ?>
                        <form id="addCmsPageForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('question', 'Security Question', ['class' => 'font-weight-bold']); ?>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <?php echo Form::text('question', $model->question, ['class' => 'form-control']); ?>
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
                                                <option value="1" {{$model->status == '1' ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$model->status == '0' ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="howitworkbutton"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                <a href="{{ url(config('app.adminPrefix').'/security-questions/index') }}">
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
@section('modals-content')
<!-- Modal for activating deactivating template -->
    <div class="modal fade" id="howItWorksIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="howItWorksIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="howItWorksIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="message">Are you sure !!.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="howItWorksIsActive">Okay</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="{{asset('public/assets/js/settings/securityquestion.js')}}"></script>
<script type="text/javascript">
    $(document).on('click','#howItWorksIsActive',function(){
    
    $("#addCmsPageForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        title: {
            required: true,
        },
        image: {
            required: true,
        },
        description:{
            required: true,
        },
    },
    messages: {
        "title": {
            required: "Please enter title"
        },
        "image":{
            required: "Please add icon with size 250 X 250px"
        },
        "description":{
            required: "Please enter short description"
        }
    },
    errorPlacement: function (error, element)
    {
        error.insertAfter(element)
    },
    submitHandler: function(form)
    {
        form.submit();
    }
    });
    
    $('#addCmsPageForm').submit();

    });
    $(document).on('click','#howitworkbutton',function(){
        $('#addCmsPageForm').submit();
    });
</script>
@endpush
