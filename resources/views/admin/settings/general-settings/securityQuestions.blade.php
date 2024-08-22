<?php

use App\Models\GlobalSettings;
?>
<div class="tab-pane" id="security_questions" role="tabpanel">
    <form id="addSecurityQuestionsForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="andriod_link" class="font-weight-bold">Minimum questions to be answered</label>
                    <div>
                        <input type="text" class="form-control" id="security_question" name="settings[security_question]" placeholder="Enter minimum question to be answered" value="{{ GlobalSettings::getSingleSettingVal('security_question') }}" />
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>