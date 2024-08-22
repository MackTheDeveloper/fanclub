@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Fan | '.config('app.name_show') : 'Add Fan | '.config('app.name_show'); ?></title>
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
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Fans</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Fans</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/fans/index')}}" style="color: grey">
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
                        <h5 class="card-title">Fan INFORMATION</h5>
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
                            $actionUrl = url(config('app.adminPrefix').'/fans/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/fans/store');
                        ?>
                        {{ Form::open(array('url' => $actionUrl,'class'=>'','id'=>'addFanForm','autocomplete'=>'off','enctype'=>"multipart/form-data")) }}
                        @csrf
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('firstname', 'Your Name', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('firstname', $model->firstname, ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('email', 'Email', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('email', $model->email, ['class' => 'form-control','disabled'=>true]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <?php echo Form::label('prefix', 'Prefix', ['class' => 'font-weight-bold']); ?>
                                <span class="text-danger">*</span>
                                <div>
                                    <select class="form-control" name="prefix">
                                        @foreach ($emojiwithcodes as $item)
                                        <option value="{{ $item->phonecode }}"> &nbsp;&nbsp;&nbsp;&nbsp;{{$item->phonecode}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php echo Form::label('phone', 'Phone', ['class' => 'font-weight-bold']); ?>
                                <span class="text-danger">*</span>
                                <div>
                                    <?php echo Form::text('phone', $model->phone, ['class' => 'form-control']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="current_subscription" class="font-weight-bold">Current Subscription
                                    <span class="text-danger">*</span>
                                </label>
                                <div>
                                    <?php echo Form::text('current_subscription', $model->current_subscription, ['class' => 'form-control', 'disabled'=>true]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('country', 'Country', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <select name="country" class="select_country form-control" id="country">
                                        <option value="">Country Of Residence*</option>
                                        @foreach ($countries as $key => $row)
                                            <option value="{{ $row['value'] }}" @if ($model->country == $row['value']) selected="selected" @endif>{{ $row['value'] }}</option>
                                        @endforeach
                                    </select>
                                    {{-- <?php echo Form::label('country', 'Country', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::text('country', $model->country, ['class' => 'form-control']); ?>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-6 label-select select-with-label show_states_select {{ $model->country == 'United States' ? '' : 'd-none' }}"  >
                                <?php echo Form::label('state', 'State', ['class' => 'font-weight-bold']); ?>
                                <span class="text-danger">*</span>
                                <select class="select select_state form-control" name="state" id="state">
                                    @if ($model->state)
                                    <option value="{{ $model->state}}" {{ $model->state ? 'selected' : '' }}>{{ $model->state }}</option>
                                    @else
                                    <option>Select...</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 label-select select-with-label show_states_input {{ $model->country == 'United States' ? 'd-none' : '' }}">
                                <label class="font-weight-bold">State*</label>
                                <input class="form-control" type="text" name="state" {{ $model->country == 'United States' ? 'disabled' : '' }}
                                value="{{ $model->state }}">
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
                                            <option value="1" <?php echo $model->is_active == '1' ? 'selected' : ''; ?>>Active</option>
                                            <option value="0" <?php echo $model->is_active == '0' ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Gender</label>
                                    <span class="text-danger">*</span>
                                    <br>
                                    <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo $model->gender == 'other' ? '<input class="custom-control-input" type="radio" name="gender" id="other" value="other" checked>' :  '<input class="custom-control-input" type="radio" name="gender" id="other" value="other">'; ?>
                                        <label class="custom-control-label" for="other">Other</label>
                                    </div>
                                    <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo $model->gender == 'male' ? '<input class="custom-control-input" type="radio" name="gender" id="male" value="male" checked>' :  '<input class="custom-control-input" type="radio" name="gender" id="male" value="male">'; ?>
                                        <label class="custom-control-label" for="male">Male</label>
                                    </div>
                                    <div class="custom-radio custom-control custom-control-inline">
                                        <?php echo $model->gender == 'female' ? '<input class="custom-control-input" type="radio" name="gender" id="female" value="female" checked>' :  '<input class="custom-control-input" type="radio" name="gender" id="female" value="female">'; ?>
                                        <label class="custom-control-label" for="female">Female</label>
                                    </div>
                                 </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('user_profile_photos', 'Profile Photo', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('user_profile_photos', ['id' => 'user_profile_photos', 'class' => '', 'value' => old('user_profile_photos')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.userImageDimention.width')}} X {{config('app.userImageDimention.height')}} px.</small>
                                    </div>
                                    @if($errors->has('user_profile_photos'))
                                        <div class="error">{{ $errors->first('user_profile_photos') }}</div>
                                    @endif
                                    <?php if (isset($model->id)) { ?>
                                    <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\UserProfilePhoto::getProfilePhoto($model->id) }}')"><img src="{{ App\Models\UserProfilePhoto::getProfilePhoto($model->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="addFan"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                    <a href="{{ url(config('app.adminPrefix').'/fans/index') }}">
                                        <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                                    </a>
                                </div>
                            </div>

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
<script src="{{asset('public/assets/js/users/fan.js')}}"></script>
<script>


$(document).on('change', '#country', function() {
            stateTextDropdown();
            var value = $(this).val();
            if (value == 'United States') {
                $.ajax({
                    url:"{{ route('stateList') }}",
                    method:'post',
                    data:'country="231"&_token={{ csrf_token() }}',
                    dataType : 'json',
                    success:function(response){
                        $('.select_state').empty();
                        $.each(response.component.stateListData.countries,function(k,v)
                        {
                            $(".select_state").append('<option value="'+v.key+'">'+v.key+'</option>');
                        });
                    }
                });
            }
        });

        function stateTextDropdown() {
            if ($('select.select_country').val() == 'United States') {
                $('.show_states_select').removeClass('d-none').find('select').removeAttr('disabled');
                $('.show_states_input').addClass('d-none').find('input').attr('disabled', 'disabled');
            } else {
                $('.show_states_select').addClass('d-none').find('select').attr('disabled', 'disabled');
                $('.show_states_input').removeClass('d-none').find('input').removeAttr('disabled');
            }
        }

    </script>
@endpush
