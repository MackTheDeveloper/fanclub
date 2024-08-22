<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane" id="other" role="tabpanel">
    <form id="addOtherSettings" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title card-title-in-page">Default Location</h5>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="default_location_area" class="font-weight-bold">Default Location Area</label>
                    <div>
                        <input type="text" class="form-control" id="default_location_area" name="settings[default_location_area]" placeholder="Enter Default Location Area" value="{{ GlobalSettings::getSingleSettingVal('default_location_area') }}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="default_location_city" class="font-weight-bold">Default Location City</label>
                    <div>
                        <input type="text" class="form-control" id="default_location_city" name="settings[default_location_city]" placeholder="Enter Default Location Area" value="{{ GlobalSettings::getSingleSettingVal('default_location_city') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title card-title-in-page">Home Page Registration</h5>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_page_registration_banner" class="font-weight-bold">Banner</label>
                    <div>
                        <input id="image" id='home_page_registration_banner' name="settings[home_page_registration_banner]" type="file">
                        <small class="form-text text-muted">Image size should be {{config('app.homePageRegistrationBanner.width')}} X {{config('app.homePageRegistrationBanner.height')}} px.</small>
                    </div>
                    <?php if (GlobalSettings::getSingleSettingVal('home_page_registration_banner')) { ?>
                        <div style="float: left"><a href="{{url('public/assets/images/homepage_register_banner/' . GlobalSettings::getSingleSettingVal('home_page_registration_banner'))}}" target="_blank"><img src="{{ url('public/assets/images/homepage_register_banner/'. GlobalSettings::getSingleSettingVal('home_page_registration_banner')) }}" width="50" height="50" alt="" /></a></div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_page_registration_banner_text" class="font-weight-bold">Text</label>
                    <div>
                        <input type="text" class="form-control" id="home_page_registration_banner_text" name="settings[home_page_registration_banner_text]" placeholder="Enter Text" value="{{ GlobalSettings::getSingleSettingVal('home_page_registration_banner_text') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>