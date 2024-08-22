<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane" id="app_links" role="tabpanel">
    <form id="addSocialLinksForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="andriod_link" class="font-weight-bold">Andriod Link</label>
                    <div>
                        <input type="text" class="form-control" id="andriod_link" name="settings[andriod_link]" placeholder="Enter Andriod Link" value="{{ GlobalSettings::getSingleSettingVal('andriod_link') }}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ios_link" class="font-weight-bold">IOS Link</label>
                    <div>
                        <input type="text" class="form-control" id="ios_link" name="settings[ios_link]" placeholder="Enter IOS Link" value="{{ GlobalSettings::getSingleSettingVal('ios_link') }}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>