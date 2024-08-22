@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit How It Works Web| '.config('app.name_show') : 'Add How It Works Web| '.config('app.name_show'); ?></title>
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
                                    <span class="d-inline-block">How It Works Web</span>
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
                                                <a href="javascript:void(0);" style="color: grey">How It Works Web</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/how-it-works/index')}}" style="color: grey">
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
                        <h5 class="card-title">How It Works Web INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/how-it-works/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/how-it-works/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addMusicCategoryForm','autocomplete'=>'off','enctype'=>'multipart/form-data')) }}
                        @csrf
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
                                    <?php echo Form::label('type', 'Type', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        {{-- <?php echo Form::text('title', $model->title, ['class' => 'form-control']); ?> --}}
                                        <div class="custom-radio custom-control custom-control-inline">
                                          <?php echo Form::radio('type', 'fan', ($model->type && $model->type=='fan')?true:false,['class' => 'custom-control-input','id'=>'fan']); ?>
                                          <label class="custom-control-label" for="fan">Fan</label>
                                        </div>
                                        <div class="custom-radio custom-control custom-control-inline">
                                          <?php echo Form::radio('type', 'artist', ($model->type && $model->type=='artist')?true:false,['class' => 'custom-control-input','id'=>'artist']); ?>
                                          <label class="custom-control-label" for="artist">Artist</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('sort_order', 'Sort Order', ['class' => 'font-weight-bold']); ?>
                                    <div>
                                        <?php echo Form::number('sort_order', $model->sort_order, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                        <label for="image" class="font-weight-bold">Image</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input name="image" id="image" type="file" class="form-control-file" value="{{old('image')}}">
                                            <small class="form-text text-muted">Image size should be {{config('app.howitworks.width')}} X {{config('app.howitworks.height')}} px.</small>
                                        </div>
                                        <?php if (isset($model->image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{$model->image}}')"><img src="{{ $model->image }}" width="50" height="50" alt="" /></a></div>
                                        <?php } ?>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('description', 'Description', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::textarea('description', $model->description, ['class' => 'form-control','rows'=>3]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="addHowItWorks"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                            <a href="{{ url(config('app.adminPrefix').'/how-it-works/index') }}">
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
<script src="{{asset('public/assets/js/cms-page/how_it_works.js')}}"></script>
<script type="text/javascript">
    $(document).on('change','input[name="type"]',function(){
        var value = $(this).val();
        if(value){
            $.ajax({
                url: "{{ url(config('app.adminPrefix').'/how-it-works/get-sort-order') }}/"+value,
                method: "GET",
                success:function(response){
                    $('input[name="sort_order"]').val(response);
                }
            });
        }
    })
</script>
@endpush
