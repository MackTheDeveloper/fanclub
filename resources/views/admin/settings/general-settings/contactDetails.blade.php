<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane active" id="contactdetails" role="tabpanel">
    <form id="updateFooterDetailsForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="about" class="font-weight-bold">About</label>
                    <div>
                        <textarea rows="7" type="text" class="form-control" id="about" name="settings[about]" placeholder="Enter About" value="{{ GlobalSettings::getSingleSettingVal('about')}}" /></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="about" class="font-weight-bold">Email Id</label>
                    <div>
                        <input type="text" class="form-control" id="about" name="settings[email_id]" placeholder="Enter Email Id" value="{{ GlobalSettings::getSingleSettingVal('email_id')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="contact_number" class="font-weight-bold">Contact Number</label>
                    <div>
                        <input type="number" class="form-control" id="contact_number" name="settings[contact_number]" placeholder="Enter Contact Number" value="{{ GlobalSettings::getSingleSettingVal('contact_number')}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp_number" class="font-weight-bold">Whatsapp Number</label>
                    <div>
                        <input type="number" class="form-control" id="whatsapp_number" name="settings[whatsapp_number]" placeholder="Enter Whatsapp Number" value="{{ GlobalSettings::getSingleSettingVal('whatsapp_number')}}" />
                    </div>
                </div>
            </div>
        
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>