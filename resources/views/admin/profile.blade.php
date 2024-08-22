@extends('admin.layouts.master')
<title>{{config('app.name_show')}} | Profile</title>

@section('content')
<div class="app-container body-tabs-shadow fixed-header fixed-sidebar app-theme-gray closed-sidebar">
    @include('admin.include.header')
    <div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Profile</span>
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
                                                <a style="color: grey" href="{{url(config('app.adminPrefix').'/dashboard')}}">Dashboard</a>
                                            </li>
                                            <li class="active breadcrumb-item" style="color: slategray" aria-current="page">
                                                Update Profile
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
                        <h5 class="card-title">Profile Information</h5>
                        <form id="adminProfile" class="" method="POST" action="{{url(config('app.adminPrefix').'/update-profile')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" name="id" value="{{$users->id}}">
                                    <div class="form-group">
                                        <label for="firstname"><strong>First Name<span style="color:red">*</span></strong></label>
                                        <div class="">
                                            <input type="text" name="firstname" class="form-control" value="{{$users->firstname}}">
                                            @if($errors->has('firstname'))
                                            <div class="custom-error">{{ $errors->first('firstname') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname"><strong>Last Name<span style="color:red">*</span></strong></label>
                                        <div class="">
                                            <input type="text" name="lastname" class="form-control" value="{{$users->lastname}}">
                                            @if($errors->has('lastname'))
                                            <div class="custom-error">{{ $errors->first('lastname') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><strong>Email</strong></label>
                                        <div class="">
                                            <input type="email" disabled name="email" class="form-control" value="{{$users->email}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><strong>Mobile<span style="color:red">*</span></strong></label>
                                        <div class="">
                                            <input type="text" name="mobile" class="form-control" value="{{$users->phone}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" style="display: none">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="timezone_offset"><strong>Timezone</strong></label>
                                        <select name="timezone_offset" id="timezone-offset" class="multiselect-dropdown form-control">
                                            <optgroup label="Select Timezone">
                                                <option value="-12:00" {{($users->zone == "-12:00") ? 'selected' : ''}}>(GMT -12:00) Eniwetok, Kwajalein</option>
                                                <option value="-11:00" {{($users->zone == "-11:00") ? 'selected' : ''}}>(GMT -11:00) Midway Island, Samoa</option>
                                                <option value="-10:00" {{($users->zone == "-10:00") ? 'selected' : ''}}>(GMT -10:00) Hawaii</option>
                                                <option value="-09:30" {{($users->zone == "-09:30") ? 'selected' : ''}}>(GMT -9:30) Taiohae</option>
                                                <option value="-09:00" {{($users->zone == "-09:00") ? 'selected' : ''}}>(GMT -9:00) Alaska</option>
                                                <option value="-08:00" {{($users->zone == "-08:00") ? 'selected' : ''}}>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                                                <option value="-07:00" {{($users->zone == "-07:00") ? 'selected' : ''}}>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                                                <option value="-06:00" {{($users->zone == "-06:00") ? 'selected' : ''}}>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                                                <option value="-05:00" {{($users->zone == "-05:00") ? 'selected' : ''}}>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                                                <option value="-04:30" {{($users->zone == "-04:30") ? 'selected' : ''}}>(GMT -4:30) Caracas</option>
                                                <option value="-04:00" {{($users->zone == "-04:00") ? 'selected' : ''}}>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                                                <option value="-03:30" {{($users->zone == "-03:30") ? 'selected' : ''}}>(GMT -3:30) Newfoundland</option>
                                                <option value="-03:00" {{($users->zone == "-03:00") ? 'selected' : ''}}>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                                                <option value="-02:00" {{($users->zone == "-02:00") ? 'selected' : ''}}>(GMT -2:00) Mid-Atlantic</option>
                                                <option value="-01:00" {{($users->zone == "-01:00") ? 'selected' : ''}}>(GMT -1:00) Azores, Cape Verde Islands</option>
                                                <option value="+00:00" {{($users->zone == "+00:00") ? 'selected' : ''}}>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                                                <option value="+01:00" {{($users->zone == "+01:00") ? 'selected' : ''}}>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
                                                <option value="+02:00" {{($users->zone == "+02:00") ? 'selected' : ''}}>(GMT +2:00) Kaliningrad, South Africa</option>
                                                <option value="+03:00" {{($users->zone == "+03:00") ? 'selected' : ''}}>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                                                <option value="+03:30" {{($users->zone == "+03:30") ? 'selected' : ''}}>(GMT +3:30) Tehran</option>
                                                <option value="+04:00" {{($users->zone == "+04:00") ? 'selected' : ''}}>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                                                <option value="+04:30" {{($users->zone == "+04:30") ? 'selected' : ''}}>(GMT +4:30) Kabul</option>
                                                <option value="+05:00" {{($users->zone == "+05:00") ? 'selected' : ''}}>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                                                <option value="+05:30" {{($users->zone == "+05:30") ? 'selected' : ''}}>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                                                <option value="+05:45" {{($users->zone == "+05:45") ? 'selected' : ''}}>(GMT +5:45) Kathmandu, Pokhara</option>
                                                <option value="+06:00" {{($users->zone == "+06:00") ? 'selected' : ''}}>(GMT +6:00) Almaty, Dhaka, Colombo</option>
                                                <option value="+06:30" {{($users->zone == "+06:30") ? 'selected' : ''}}>(GMT +6:30) Yangon, Mandalay</option>
                                                <option value="+07:00" {{($users->zone == "+07:00") ? 'selected' : ''}}>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                                                <option value="+08:00" {{($users->zone == "+08:00") ? 'selected' : ''}}>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                                                <option value="+08:45" {{($users->zone == "+08:45") ? 'selected' : ''}}>(GMT +8:45) Eucla</option>
                                                <option value="+09:00" {{($users->zone == "+09:00") ? 'selected' : ''}}>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                                                <option value="+09:30" {{($users->zone == "+09:30") ? 'selected' : ''}}>(GMT +9:30) Adelaide, Darwin</option>
                                                <option value="+10:00" {{($users->zone == "+10:00") ? 'selected' : ''}}>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                                                <option value="+10:30" {{($users->zone == "+10:30") ? 'selected' : ''}}>(GMT +10:30) Lord Howe Island</option>
                                                <option value="+11:00" {{($users->zone == "+11:00") ? 'selected' : ''}}>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                                                <option value="+11:30" {{($users->zone == "+11:30") ? 'selected' : ''}}>(GMT +11:30) Norfolk Island</option>
                                                <option value="+12:00" {{($users->zone == "+12:00") ? 'selected' : ''}}>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                                                <option value="+12:45" {{($users->zone == "+12:45") ? 'selected' : ''}}>(GMT +12:45) Chatham Islands</option>
                                                <option value="+13:00" {{($users->zone == "+13:00") ? 'selected' : ''}}>(GMT +13:00) Apia, Nukualofa</option>
                                                <option value="+14:00" {{($users->zone == "+14:00") ? 'selected' : ''}}>(GMT +14:00) Line Islands, Tokelau</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="timezone" id="timezone">
                            <div class="form-group">
                                <div class="">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection
<div class="app-drawer-overlay d-none animated fadeIn"></div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#timezone').val(moment.tz.guess());
    })
</script>
@endpush