<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane" id="social_links" role="tabpanel">
    <form id="addSocialLinksForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fb_link" class="font-weight-bold">Facebook Link</label>
                    <div>
                        <input type="text" class="form-control" id="fb_link" name="settings[fb_link]" placeholder="Enter FB Link" value="{{ GlobalSettings::getSingleSettingVal('fb_link') }}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="insta_link" class="font-weight-bold">Instagram Link</label>
                    <div>
                        <input type="text" class="form-control" id="insta_link" name="settings[insta_link]" placeholder="Enter Instagram Link" value="{{ GlobalSettings::getSingleSettingVal('insta_link') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="youtube_link" class="font-weight-bold">Youtube Link</label>
                    <div>
                        <input type="text" class="form-control" id="youtube_link" name="settings[youtube_link]" placeholder="Enter Youtube Link" value="{{ GlobalSettings::getSingleSettingVal('youtube_link') }}" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="twitter_link" class="font-weight-bold">Twitter Link</label>
                    <div>
                        <input type="text" class="form-control" id="twitter_link" name="settings[twitter_link]" placeholder="Enter Twitter Link" value="{{ GlobalSettings::getSingleSettingVal('twitter_link') }}" />
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="twitter_link" class="font-weight-bold">Linked_In Link</label>
                    <div>
                        <input type="text" class="form-control" id="linkedin_link" name="settings[linkedin_link]" placeholder="Enter Twitter Link" value="{{ GlobalSettings::getSingleSettingVal('linkedin_link') }}" />
                    </div>
                </div>
            </div> --}}
        </div>
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="youtube_link" class="font-weight-bold">Support Email</label>
                    <div>
                        <input type="text" class="form-control" id="support_email" name="settings[support_email]" placeholder="Enter Support Email" value="{{ GlobalSettings::getSingleSettingVal('support_email') }}" />
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>