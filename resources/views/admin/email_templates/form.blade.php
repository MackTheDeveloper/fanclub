@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Email Template | '.config('app.name_show') : 'Add Email Template | '.config('app.name_show'); ?></title>
<?php
if (!empty($model->id)) {
    $countCc = App\Models\EmailTemplatesCc::where('template_id', $model->id)->count();
} else {
    $countCc = 0;
} ?>
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
                                    <span class="d-inline-block">Email Templates</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Email Templates</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/email-templates/index')}}" style="color: grey">List</a>
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
                        <h5 class="card-title">Email Template INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/email-templates/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/email-templates/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addEmailTemplateForm','autocomplete'=>'off')) }}
                        @csrf
                        <?php if ($model->id) { ?>
                            <input type="hidden" name="id" value="<?php echo $model->id; ?>" />
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('title', 'Title', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('title', $model->title, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('slug', 'Slug', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('slug', $model->slug, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('subject', 'Subject', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('subject', $model->subject, ['class' => 'form-control']); ?>
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
                                            <option value="1" <?php echo $model->is_active == '1' ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?php echo $model->is_active == '0' ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?php echo Form::label('body', 'Body', ['class' => 'font-weight-bold']); ?>
                                    {{-- <span class="text-danger">*</span> --}}
                                    <div>
                                        <?php echo Form::textarea('body', $model->body, ['class' => 'form-control','id'=>'ckeditorBody']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row rowForCc">
                            <?php if (empty($model->id) || empty($dataEmailCc)) { ?>
                                <div class="col-md-12" id="add-0">
                                    <div class="col-md-7 row">
                                        <div class="col-md-10">

                                            <div class="form-group">

                                                <?php echo Form::label('email_cc', 'Email Cc', ['class' => 'font-weight-bold']); ?>
                                                {{-- <span class="text-danger">*</span> --}}
                                                <div>
                                                    <?php echo Form::email('emailCc[email_cc][0]', '', ['class' => 'form-control']); ?>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group" style="margin-top:35px">
                                                <div>
                                                    <a href="javascript:void(0)" class='btn btn-primary btn-xs addmore'>+</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } else {
                                $i = 0;
                                foreach ($dataEmailCc as $k => $v) { ?>
                                    <div class="col-md-12" id="add-<?php echo $i; ?>">
                                        <div class="col-md-7 row">
                                            <div class="col-md-10">

                                                <div class="form-group">
                                                    <?php if ($i == 0) { ?>
                                                        <?php echo Form::label('email_cc', 'Email Cc', ['class' => 'font-weight-bold']); ?>
                                                        {{-- <span class="text-danger">*</span> --}}
                                                    <?php } ?>
                                                    <div>
                                                        <?php echo Form::email("emailCc[email_cc][$i]", $v->email_cc, ['class' => 'form-control']); ?>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" style="margin-top:15px">
                                                    <div>
                                                        <a style="margin-right:5px" href="javascript:void(0)" class='btn btn-success btn-xs addmore'>+</a>
                                                        <?php if ($i != 0) { ?>
                                                            <a href='javascript:void(0)' class='btn btn-danger btn-xs removemore' id=<?php echo $i; ?>>-</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php $i++;
                                }
                            } ?>
                        </div>





                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addTemplate"><?php echo $model->id ? 'Update' : 'Add'; ?>
                                </button>
                            <a href="{{ url(config('app.adminPrefix').'/email-templates/index') }}">
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
<script src="{{asset('public/assets/js/email_templates/email_templates.js')}}"></script>
<script>
    let countCc = <?php echo $countCc; ?>;
    if (countCc != 0) {
        countCc = countCc - 1;
    }
    var editor = CKEDITOR.replace( 'ckeditorBody', {
        filebrowserUploadUrl: "{{route('ckeditor.upload_email_image', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        allowedContent: true
    } );
</script>
@endpush
