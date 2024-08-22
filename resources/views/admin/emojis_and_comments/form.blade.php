@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Emojis & Comments | '.config('app.name_show') : 'Add Emojis & Comments | '.config('app.name_show'); ?></title>
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
                                    <span class="d-inline-block">Emojis & Comments</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Emojis & Comments</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/emojis-and-comments/index')}}" style="color: grey">
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
                        <h5 class="card-title">Emojis & Comments INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/emojis-and-comments/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/emojis-and-comments/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addMusicCategoryForm','autocomplete'=>'off','enctype'=>'multipart/form-data')) }}
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('type', 'Type', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                      <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo Form::radio('type', 'icon', isset($model->type) ? (($model->type && $model->type=='icon')?true:false): true ,['class' => 'custom-control-input','id'=>'icon']); ?>
                                        <label class="custom-control-label" for="icon">Icon</label>
                                      </div>
                                      <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo Form::radio('type', 'comment', ($model->type && $model->type=='comment')?true:false,['class' => 'custom-control-input','id'=>'comment']); ?> 
                                        <label class="custom-control-label" for="comment">Comment</label>
                                      </div>{{--
                                        <?php echo Form::radio('type', 'icon',array('id'=>'icon'), ( $model->type && $model->type=='icon') ? true : false ) ?> Icon
                                        <?php echo Form::radio('type', 'comment',array('id'=>'comment'), ( $model->type && $model->type=='comment') ? true : false ) ?> Comment--}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('sort_order', 'Sort Order', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::number('sort_order', $model->sort_order, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" id="imageId">
                                <div class="form-group" >
                                    <?php echo Form::label('image', 'Image', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('image', ['id' => 'image', 'class' => '', 'value' => old('image')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.emojiIcon.width')}} X {{config('app.emojiIcon.height')}} px.</small>
                                    </div>
                                    @if($errors->has('image'))
                                        <div class="error">{{ $errors->first('image') }}</div>
                                    @endif
                                    <?php if (isset($model->id)) { ?>
                                    <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\EmojisAndComments::getProfilePhoto($model->id) }}')"><img src="{{ App\Models\EmojisAndComments::getProfilePhoto($model->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>

                                </div>
                            </div>
                            <div class="col-md-6" id="commentId">
                                <div class="form-group" >
                                    <?php echo Form::label('comment', 'Comments', ['id'=> 'comment','class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('comment', $model->comment, ['class' => 'form-control','rows'=>3]); ?>
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
                                            <option value="1" <?php echo $model->status == '1' ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?php echo $model->status == '0' ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="error_print"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addHowItWorks"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/emojis-and-comments/index') }}">
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
<script src="{{asset('public/assets/js/settings/emojiandcontroller.js')}}"></script>
@endpush
