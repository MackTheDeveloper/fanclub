@extends('admin.layouts.master')
@section('title','Edit Professionals')
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
                                <span class="d-inline-block">Artist</span>
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
                                            <a href="javascript:void(0)" style="color: grey">Artist</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="{{url(config('app.adminPrefix').'/professional/list')}}" style="color: grey">List</a>
                                        </li>
                                        <li class="active breadcrumb-item" aria-current="page">
                                           <a style="color: slategray">Edit</a>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="addNewProfessional" method="post" action="{{url(config('app.adminPrefix').'/professional/update')}}" enctype="multipart/form-data">
                @csrf
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Basic Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="first_name">First Name<span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" value="{{$user->firstname}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" value="{{$user->lastname}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone<span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" value="{{$user->phone}}" />
                                    </div>
                                    @if($errors->has('phone'))
                                    <div class="error">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="city">City<span class="text-danger">*</span></label>
                                    <div>
                                        <select class="form-control" name="city">
                                            <option value="">Select City</option>
                                            @foreach($city as $key=>$val)
                                                <option @if($user->city == $val) selected="selected" @endif value="{{$val}}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email<span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter email" value="{{$user->email}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="timezone_offset">Timezone</label>
                                    <select name="timezone_offset" id="timezone-offset" class="multiselect-dropdown form-control">
                                        <optgroup label="Select Timezone">
                                            <option value="-12:00" {{($user->tz_offset == "-12:00") ? 'selected' : ''}}>(GMT -12:00) Eniwetok, Kwajalein</option>
                                            <option value="-11:00" {{($user->tz_offset == "-11:00") ? 'selected' : ''}}>(GMT -11:00) Midway Island, Samoa</option>
                                            <option value="-10:00" {{($user->tz_offset == "-10:00") ? 'selected' : ''}}>(GMT -10:00) Hawaii</option>
                                            <option value="-09:50" {{($user->tz_offset == "-09:50") ? 'selected' : ''}}>(GMT -9:30) Taiohae</option>
                                            <option value="-09:00" {{($user->tz_offset == "-09:00") ? 'selected' : ''}}>(GMT -9:00) Alaska</option>
                                            <option value="-08:00" {{($user->tz_offset == "-08:00") ? 'selected' : ''}}>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                            <option value="-07:00" {{($user->tz_offset == "-07:00") ? 'selected' : ''}}>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                            <option value="-06:00" {{($user->tz_offset == "-06:00") ? 'selected' : ''}}>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                            <option value="-05:00" {{($user->tz_offset == "-05:00") ? 'selected' : ''}}>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                            <option value="-04:50" {{($user->tz_offset == "-04:50") ? 'selected' : ''}}>(GMT -4:30) Caracas</option>
                                            <option value="-04:00" {{($user->tz_offset == "-04:00") ? 'selected' : ''}}>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                            <option value="-03:50" {{($user->tz_offset == "-03:50") ? 'selected' : ''}}>(GMT -3:30) Newfoundland</option>
                                            <option value="-03:00" {{($user->tz_offset == "-03:00") ? 'selected' : ''}}>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                            <option value="-02:00" {{($user->tz_offset == "-02:00") ? 'selected' : ''}}>(GMT -2:00) Mid-Atlantic</option>
                                            <option value="-01:00" {{($user->tz_offset == "-01:00") ? 'selected' : ''}}>(GMT -1:00) Azores, Cape Verde Islands</option>
                                            <option value="+00:00" {{($user->tz_offset == "+00:00") ? 'selected' : ''}}>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                            <option value="+01:00" {{($user->tz_offset == "+01:00") ? 'selected' : ''}}>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
                                            <option value="+02:00" {{($user->tz_offset == "+02:00") ? 'selected' : ''}}>(GMT +2:00) Kaliningrad, South Africa</option>
                                            <option value="+03:00" {{($user->tz_offset == "+03:00") ? 'selected' : ''}}>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                            <option value="+03:50" {{($user->tz_offset == "+03:50") ? 'selected' : ''}}>(GMT +3:30) Tehran</option>
                                            <option value="+04:00" {{($user->tz_offset == "+04:00") ? 'selected' : ''}}>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                            <option value="+04:50" {{($user->tz_offset == "+04:50") ? 'selected' : ''}}>(GMT +4:30) Kabul</option>
                                            <option value="+05:00" {{($user->tz_offset == "+05:00") ? 'selected' : ''}}>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                            <option value="+05:50" {{($user->tz_offset == "+05:50") ? 'selected' : ''}}>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                            <option value="+05:75" {{($user->tz_offset == "+05:75") ? 'selected' : ''}}>(GMT +5:45) Kathmandu, Pokhara</option>
                                            <option value="+06:00" {{($user->tz_offset == "+06:00") ? 'selected' : ''}}>(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                            <option value="+06:50" {{($user->tz_offset == "+06:50") ? 'selected' : ''}}>(GMT +6:30) Yangon, Mandalay</option>
                                            <option value="+07:00" {{($user->tz_offset == "+07:00") ? 'selected' : ''}}>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                            <option value="+08:00" {{($user->tz_offset == "+08:00") ? 'selected' : ''}}>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                            <option value="+08:75" {{($user->tz_offset == "+08:75") ? 'selected' : ''}}>(GMT +8:45) Eucla</option>
                                            <option value="+09:00" {{($user->tz_offset == "+09:00") ? 'selected' : ''}}>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                            <option value="+09:50" {{($user->tz_offset == "+09:50") ? 'selected' : ''}}>(GMT +9:30) Adelaide, Darwin</option>
                                            <option value="+10:00" {{($user->tz_offset == "+10:00") ? 'selected' : ''}}>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                            <option value="+10:50" {{($user->tz_offset == "+10:50") ? 'selected' : ''}}>(GMT +10:30) Lord Howe Island</option>
                                            <option value="+11:00" {{($user->tz_offset == "+11:00") ? 'selected' : ''}}>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                            <option value="+11:50" {{($user->tz_offset == "+11:50") ? 'selected' : ''}}>(GMT +11:30) Norfolk Island</option>
                                            <option value="+12:00" {{($user->tz_offset == "+12:00") ? 'selected' : ''}}>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                            <option value="+12:75" {{($user->tz_offset == "+12:75") ? 'selected' : ''}}>(GMT +12:45) Chatham Islands</option>
                                            <option value="+13:00" {{($user->tz_offset == "+13:00") ? 'selected' : ''}}>(GMT +13:00) Apia, Nukualofa</option>
                                            <option value="+14:00" {{($user->tz_offset == "+14:00") ? 'selected' : ''}}>(GMT +14:00) Line Islands, Tokelau</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alternate_phone_number">Alternate Phone</label>
                                    <div>
                                        <input type="text" class="form-control" id="alternate_phone_number" name="alternate_phone_number" placeholder="Enter alternate phone number" value="{{$user->alternate_phone_number}}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="area">Area<span class="text-danger">*</span></label>
                                    <div>
                                        {{-- <input type="text" class="form-control" id="area" name="area" placeholder="Enter area" value="{{$user->area}}" /> --}}
                                        <select class="form-control" name="area">
                                            <option value="">Select Area</option>
                                            @foreach($areas as $key=>$val)
                                                <option @if($user->area == $val) selected="selected" @endif value="{{$val}}">{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($errors->has('area'))
                                    <div class="error">{{ $errors->first('area') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('profile_pic', 'Profile Pic', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('profile_pic', ['id' => 'profile_pic', 'class' => '', 'value' => old('profile_pic')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.userImageDimention.width')}} X {{config('app.userImageDimention.height')}} px.</small>
                                    </div>
                                    @if($errors->has('profile_pic'))
                                    <div class="error">{{ $errors->first('profile_pic') }}</div>
                                    @endif
                                    <?php if (isset($user->id)) { ?>
                                        <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\UserProfilePhoto::getProfilePhoto($user->id) }}')"><img src="{{ App\Models\UserProfilePhoto::getProfilePhoto($user->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="timezone" id="timezone">
                        <!-- <div class="form-group">
                                    <label for="password">Password</label>
                                    <div>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <div>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password"/>
                                    </div>
                                </div>                          -->


                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Professonal Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('category_id', 'Product Category', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::select('category_id[]', $productCategories, $professional->categories, ['class' => 'form-control multiselect-dropdown', 'multiple']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('professional_category_id', 'Professional Category', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::select('professional_category_id[]', $professionalCategories, $professional->professional_categories, ['class' => 'form-control multiselect-dropdown', 'multiple']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                    <div>
                                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter first name" value="{{ $professional->company_name }}" />
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('cover_pic', 'Cover Photo', ['class' => 'font-weight-bold']); ?>
                                    <span class="text-danger">*</span>
                                    <div>
                                        <?php echo Form::file('cover_pic', ['id' => 'cover_pic', 'class' => '', 'value' => old('cover_pic')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.userCoverDimention.width')}} X {{config('app.userCoverDimention.height')}} px.</small>
                                    </div>
                                    @if($errors->has('cover_pic'))
                                    <div class="error">{{ $errors->first('cover_pic') }}</div>
                                    @endif
                                    <?php if (isset($user->id)) { ?>
                                        <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\UserCoverPhoto::getCoverPhoto($user->id) }}')"><img src="{{ App\Models\UserCoverPhoto::getCoverPhoto($user->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="work_exp">Work Experience<span class="text-danger">*</span></label>
                                    <div>
                                        <textarea class="form-control" name="work_exp" id="work_exp">{{ $professional->work_exp }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="work_exp">Team Members/No. of Employees</label>
                                    <div>
                                        <input type="text" class="form-control" id="team_members" name="team_members" placeholder="Enter Team Members" value="{{ old('team_members') }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="about">About<span class="text-danger">*</span></label>
                                    <div>
                                        <textarea class="form-control" name="about" id="about">{{ $professional->about }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="skills">Skills</label>
                                    <?php $skills = explode(',', $professional->skills) ?>
                                    @for($i=0;$i<4;$i++) <input class="form-control mb-2" name="skills[]" value="{{ isset($skills[$i])?$skills[$i]:'' }}">
                                        @endfor
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address<span class="text-danger">*</span></label>
                                    <div>
                                        <textarea class="form-control" name="address" id="address">{{ $professional->address }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <?php echo Form::label('portfolio', 'Portfolio', ['class' => 'font-weight-bold']); ?>
                                    <!-- <span class="text-danger">*</span> -->
                                    <div>
                                        <?php echo Form::file('portfolio', ['id' => 'portfolio', 'class' => '', 'value' => old('portfolio')]); ?>
                                        <small class="form-text text-muted">File should be in PDF Format.</small>
                                    </div>
                                    @if($errors->has('portfolio'))
                                    <div class="error">{{ $errors->first('portfolio') }}</div>
                                    @endif
                                    @if (isset($user->id))
                                    @if(App\Models\UserPortfolio::getFile($user->id))
                                    <a target="_blank" href="{{ App\Models\UserPortfolio::getFile($user->id) }}"><i class="fa fa-file"></i></a>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_subscribed">Is Subscribed?<span class="text-danger">*</span></label>
                                    <div>
                                        <select class="form-control" name="is_subscribed">
                                            <option value="1" {{ $professional->is_subscribed == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $professional->is_subscribed == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Is Active?<span class="text-danger">*</span></label>
                                    <div>
                                        <select class="form-control" name="status">
                                            <option value="1" {{ $professional->status == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $professional->status == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Hero Banners</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('category_hero', 'Location Hero', ['class' => 'font-weight-bold']); ?>
                                    <!-- <span class="text-danger">*</span> -->
                                    <div>
                                        <?php echo Form::file('category_hero', ['id' => 'category_hero', 'class' => '', 'value' => old('category_hero')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.userCategoryHero.width')}} X {{config('app.userCategoryHero.height')}} px.</small>
                                    </div>
                                    @if($errors->has('category_hero'))
                                    <div class="error">{{ $errors->first('category_hero') }}</div>
                                    @endif
                                    <?php if (isset($user->id)) { ?>
                                        <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\UserCategoryPhoto::getCategoryPhoto($user->id) }}')"><img src="{{ App\Models\UserCategoryPhoto::getCategoryPhoto($user->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('location_hero', 'Ad Hero', ['class' => 'font-weight-bold']); ?>
                                    {{-- <?php echo Form::label('location_hero', 'Category/Advertise Hero', ['class' => 'font-weight-bold']); ?> --}}
                                    <!-- <span class="text-danger">*</span> -->
                                    <div>
                                        <?php echo Form::file('location_hero', ['id' => 'location_hero', 'class' => '', 'value' => old('location_hero')]); ?>
                                        <small class="form-text text-muted">Image size should be {{config('app.userLocationHero.width')}} X {{config('app.userLocationHero.height')}} px.</small>
                                    </div>
                                    @if($errors->has('location_hero'))
                                    <div class="error">{{ $errors->first('location_hero') }}</div>
                                    @endif
                                    <?php if (isset($user->id)) { ?>
                                        <div style="float: left"><a href="javascript:void(0)" onclick="openImageModal('{{ App\Models\UserLocationPhoto::getLocationPhoto($user->id) }}')"><img src="{{ App\Models\UserLocationPhoto::getLocationPhoto($user->id) }}" width="50" height="50" alt="" /></a></div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>



                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary" name="update_role" value="update_role">Update</button>
                            <a href="{{url(config('app.adminPrefix').'/professional/list')}}"><button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button></a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        @include('admin.include.footer')
    </div>
</div>
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#timezone').val(moment.tz.guess());
    })

    $("#addNewProfessional").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                phone: {
                    required: true,
                    number: true
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                },
                company_name: {
                    required: true,
                },
                about: {
                    required: true,
                },
                work_exp: {
                    required: true,
                },
                city: {
                    required: true,
                },
                area: {
                    required: true,
                },
                // profile_pic: {
                //     required: true,
                //     // accept: "image/jpg,image/jpeg,image/png,image/gif"
                // },
                // cover_pic: {
                //     required: true,
                //     // accept: "image/jpg,image/jpeg,image/png,image/gif"
                // },
            },
            messages: {
                first_name: "Please enter first name",
                last_name: "Please enter last name",
                phone: {
                    required: "Please enter phone number",
                    number: "Phone number must be in digit"
                },
                email: {
                    required: 'Please enter email address',
                    email: 'Please enter a valid email address',
                },
                password: {
                    required: "Please enter password",
                    minlength: "Password must be at least 6 digit"
                },
                confirm_password: {
                    required: "Please enter confirm password",
                    equalTo: "Confirm password is not same as password"
                },
                company_name: "Please enter company name",
                city: "Please enter city",
                area: "Please enter area",
                profile_pic: "Please select Profile Photo",
                cover_pic: "Please select Cover Photo",
            },
            errorPlacement: function(error, element) {
                // Add the `invalid-feedback` class to the error element
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
        });
    $(document).on('change','select[name="city"]',function(){
        var value = $(this).val();
        var token = @json(csrf_token());
        $.ajax({
            url:'{{ route('getAreaByCity') }}',
            method:'POST',
            data:{city:value,_token:token},
            success:function(response){
                $('select[name="area"]').html(response);
            }
        })
    })
</script>
@endpush
