@php
use App\Models\GlobalSettings;
@endphp

<div class="tab-pane" id="preferences" role="tabpanel">
    <form id="addSocialLinksForm" class="" method="post" action="{{ url(config('app.adminPrefix').'/settings') }}">
        @csrf
        {{-- Hope Page SEO Start --}}
        <h5 class="card-title">Home Page SEO INFORMATION</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="home_seo_title" class="font-weight-bold">SEO Title</label>
                    <div>
                        <input type="text" class="form-control" id="home_seo_title" name="settings[home_seo_title]" placeholder="Enter SEO Title" value="{{ GlobalSettings::getSingleSettingVal('home_seo_title') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_seo_meta_keyword" class="font-weight-bold">SEO Meta Keyword</label>
                    <div>
                        {{Form::textarea('settings[home_seo_meta_keyword]', GlobalSettings::getSingleSettingVal('home_seo_meta_keyword'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home_seo_description" class="font-weight-bold">SEO Description</label>
                    <div>
                        {{Form::textarea('settings[home_seo_description]', GlobalSettings::getSingleSettingVal('home_seo_description'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
        </div>
        {{-- Hope Page SEO End --}}
        {{-- Forums SEO Start --}}
        <h5 class="card-title">Forums SEO INFORMATION</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for=forums_seo_title" class="font-weight-bold">SEO Title</label>
                    <div>
                        <input type="text" class="form-control" id="forums_seo_title" name="settings[forums_seo_title]" placeholder="Enter SEO Title" value="{{ GlobalSettings::getSingleSettingVal('forums_seo_title') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="forums_seo_meta_keyword" class="font-weight-bold">SEO Meta Keyword</label>
                    <div>
                        {{Form::textarea('settings[forums_seo_meta_keyword]', GlobalSettings::getSingleSettingVal('forums_seo_meta_keyword'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="forums_seo_description" class="font-weight-bold">SEO Description</label>
                    <div>
                        {{Form::textarea('settings[forums_seo_description]', GlobalSettings::getSingleSettingVal('forums_seo_description'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
        </div>
        {{-- Forums SEO End --}}
        {{-- FAQs SEO Start --}}
        <h5 class="card-title">FAQs SEO INFORMATION</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for=faq_seo_title" class="font-weight-bold">SEO Title</label>
                    <div>
                        <input type="text" class="form-control" id="faq_seo_title" name="settings[faq_seo_title]" placeholder="Enter SEO Title" value="{{ GlobalSettings::getSingleSettingVal('faq_seo_title') }}" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="faq_seo_meta_keyword" class="font-weight-bold">SEO Meta Keyword</label>
                    <div>
                        {{Form::textarea('settings[faq_seo_meta_keyword]', GlobalSettings::getSingleSettingVal('faq_seo_meta_keyword'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="faq_seo_description" class="font-weight-bold">SEO Description</label>
                    <div>
                        {{Form::textarea('settings[faq_seo_description]', GlobalSettings::getSingleSettingVal('faq_seo_description'), ['class' => 'form-control', 'rows' => '3'])}}
                    </div>
                </div>
            </div>
        </div>
        {{-- FAQs SEO End --}}

        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="addFooterDetails">Update</button>
        </div>
    </form>
</div>