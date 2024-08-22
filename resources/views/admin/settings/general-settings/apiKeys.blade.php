<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane active" id="api_keys" role="tabpanel">
    <form id="updateFooterDetailsForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sms_api_key" class="font-weight-bold">SMS API KEY</label>
                    <div>
                        <input type="text" class="form-control" id="sms_api_key" name="settings[sms_api_key]" placeholder="Enter API Key" value="{{ GlobalSettings::getSingleSettingVal('sms_api_key')}}" />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="otp_validity_min" class="font-weight-bold">OTP Validity (min)</label>
                    <div>
                        <input type="number" min="1" class="form-control" id="otp_validity_min" name="settings[otp_validity_min]" placeholder="Enter OTP Validity" value="{{ GlobalSettings::getSingleSettingVal('otp_validity_min')}}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="google_api_key" class="font-weight-bold">GOOGLE API KEY (WEB)</label>
                    <div>
                        <input type="text" class="form-control" id="google_api_key" name="settings[google_api_key]" placeholder="Enter API Key" value="{{ GlobalSettings::getSingleSettingVal('google_api_key')}}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="google_api_key_android" class="font-weight-bold">GOOGLE API KEY (ANDROID)</label>
                    <div>
                        <input type="text" class="form-control" id="google_api_key_android" name="settings[google_api_key_android]" placeholder="Enter API Key" value="{{ GlobalSettings::getSingleSettingVal('google_api_key_android')}}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="google_api_key_ios" class="font-weight-bold">GOOGLE API KEY (IOS)</label>
                    <div>
                        <input type="text" class="form-control" id="google_api_key_ios" name="settings[google_api_key_ios]" placeholder="Enter API Key" value="{{ GlobalSettings::getSingleSettingVal('google_api_key_ios')}}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>