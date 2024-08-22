@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit Home Page Components | ' . config('app.name_show') : 'Add Home Page Components | ' . config('app.name_show'); ?></title>
<script type="text/javascript">
    var baseUrl = <?php echo json_encode($baseUrl); ?>;
    var type = <?php echo json_encode($model->type); ?>;
    var url_type = <?php echo json_encode($model->banner_url_type); ?>;
    var url_type_id = <?php echo json_encode($model->banner_url_type_id); ?>;
</script>
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
                                        <i class="fa pe-7s-home"></i>
                                    </span>
                                    <span class="d-inline-block">Home page Components</span>
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
                                                <a href="javascript:void(0);" style="color: grey">Homepage Components</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/homepage-component/index')}}" style="color: grey">List</a>
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
                        <h5 class="card-title">Home page Components INFORMATION</h5>
                        <?php
                        if ($model->id)
                            $actionUrl = url(config('app.adminPrefix').'/homepage-component/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/homepage-component/store');
                        ?>
                        <form id="addHomePageComponentForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="font-weight-bold">Name</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$model->name}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="position-relative form-group">
                                      <label for="types"><strong>Visibility</strong></label>
                                      <span class="text-danger">*</span>
                                      <div class="position-relative form-group">
                                          <div>
                                             <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="visible1" name="visibility"  class="custom-control-input" value="1" <?php if(isset($model->visibility)){ echo $model->visibility == '1' ? 'checked' : '';}else echo 'checked'; ?>>
                                                  <label class="custom-control-label" for="visible1">Both</label>
                                              </div>
                                              <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="visible2" name="visibility" class="custom-control-input" value="2" <?php echo $model->visibility == '2' ? 'checked' : ''; ?>>
                                                  <label class="custom-control-label" for="visible2">Guest User</label>
                                              </div>
                                              <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="visible3" name="visibility" class="custom-control-input" value="3" <?php echo $model->visibility == '3' ? 'checked' : ''; ?>>
                                                  <label class="custom-control-label" for="visible3">Registered User</label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                </div>
                                <div class="col-sm-6">
                                  <div class="position-relative form-group">
                                      <label for="type"><strong>Type</strong></label>
                                      <span class="text-danger">*</span>
                                      <div class="position-relative form-group">
                                          <div>
                                              <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="type" name="type"  class="custom-control-input" value="1" <?php if(isset($model->type)){ echo $model->type == '1' ? 'checked' : '';}else echo 'checked'; ?>>
                                                  <label class="custom-control-label" for="type">Text</label>
                                              </div>
                                              <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="type2" name="type" class="custom-control-input" value="2" <?php echo $model->type == '2' ? 'checked' : ''; ?>>
                                                  <label class="custom-control-label" for="type2">Banner</label>
                                              </div>
                                              <div class="custom-radio custom-control custom-control-inline">
                                                  <input type="radio" id="type3" name="type" class="custom-control-input" value="3" <?php echo $model->type == '3' ? 'checked' : ''; ?>>
                                                  <label class="custom-control-label" for="type3">Dynamic Groups</label>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image" class="font-weight-bold">Image</label>
                                        <div>
                                            <input name="image" id="image" type="file" class="form-control-file" value="{{old('image')}}">
                                            <small class="form-text text-muted">Image size should be {{config('app.homePageImageHeight.width')}} X {{config('app.homePageImageHeight.height')}} px.</small>
                                        </div>
                                        <?php if (isset($model->image)) { ?>
                                        <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('{{ url("public/admin/homepagebanner/". $model->image) }}')"><img src="{{ url('public/admin/homepagebanner/'. $model->image) }}" width="50" height="50" alt="" /></a></div>
                                        <?php } ?>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6 bannerImage">
                                  <div class="form-group">
                                      <label for="image" class="font-weight-bold">Banner Image</label>
                                      <div>
                                          <input name="banner_image" id="banner_image" type="file" class="form-control-file" value="{{old('image')}}">
                                          <small class="form-text text-muted">Image size should be {{config('app.homePageComponentImage.width')}} X {{config('app.homePageComponentImage.height')}} px.</small>
                                      </div>
                                      <?php if (isset($model->banner_image)) { ?>
                                      <div style="float: left"><a href="javascript:void(0);" onclick="openImageModal('<?php echo url("public/assets/images/homepagecomponentbanner/". $model->banner_image); ?>')"><img src="{{ url('public/assets/images/homepagecomponentbanner/'. $model->banner_image) }}" width="50" height="50" alt="" /></a></div>
                                      <?php } ?>
                                  </div>
                              </div>
                              <div class="col-sm-6 bannerImage">
                                <div class="position-relative form-group">
                                    <label for="type"><strong>URL Type</strong></label>
                                    <div class="position-relative form-group">
                                        <div>
                                            <div class="custom-radio custom-control custom-control-inline">
                                                <input type="radio" id="banner_url_type" name="banner_url_type"  class="custom-control-input bannerUrlType" value="0" <?php echo $model->banner_url_type == '0' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type">None</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline">
                                                <input type="radio" id="banner_url_type1" name="banner_url_type" class="custom-control-input bannerUrlType" value="1" <?php echo $model->banner_url_type == '1' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type1">Sign Up</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline">
                                                <input type="radio" id="banner_url_type2" name="banner_url_type" class="custom-control-input bannerUrlType" value="2" <?php echo $model->banner_url_type == '2' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type2">Artist</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline d-none">
                                                <input type="radio" id="banner_url_type3" name="banner_url_type" class="custom-control-input bannerUrlType" value="3" <?php echo $model->banner_url_type == '3' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type3">Genre</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline d-none">
                                                <input type="radio" id="banner_url_type4" name="banner_url_type" class="custom-control-input bannerUrlType" value="4" <?php echo $model->banner_url_type == '4' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type4">Category</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline d-none">
                                                <input type="radio" id="banner_url_type5" name="banner_url_type" class="custom-control-input bannerUrlType" value="5" <?php echo $model->banner_url_type == '5' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type5">Language</label>
                                            </div>
                                            <div class="custom-radio custom-control custom-control-inline">
                                                <input type="radio" id="banner_url_type6" name="banner_url_type" class="custom-control-input bannerUrlType" value="6" <?php echo $model->banner_url_type == '6' ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="banner_url_type6">Song</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="col-md-6 urlCategory">
                                  <div class="form-group">
                                      <label class="font-weight-bold">Url Type List</label>
                                        <select name="banner_url_type_id" id="banner_url_type_id" class="multiselect-dropdown form-control" >
                                            <option value="">Please select</option>
                                        </select>
                                  </div>
                              </div>
                              <div class="col-md-12 componentText">
                                  <div class="form-group">
                                      <label for="content" class="font-weight-bold">Text
                                      </label>
                                      <div>
                                          <textarea name="text" id="text" type="text" class="form-control ckeditor">{{$model->text}}</textarea>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-6 dynamicGroups">
                                  <div class="form-group">
                                      <label class="font-weight-bold">Dynamic Groups</label>
                                        <select name="dynamic_group_id" id="dynamic_group_id" class="multiselect-dropdown form-control" >
                                            <option value="">Select Dynamic Groups</option>
                                            @if(isset($DynamicGroups))
                                            @foreach($DynamicGroups as $val)
                                            <option value="{{$val->id}}" <?php echo $model->dynamic_group_id == $val->id ? 'selected' : ''; ?>>{{$val->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group dynamicGroupsLink"></div>
                              </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_title" class="font-weight-bold">Sort Order</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="sort_order" name="sort_order" placeholder="Enter Sort Order" value="{{$model->sort_order}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Status
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div>
                                          <select name="status" id="status" class="form-control">
                                              <option value="1" <?php echo $model->status == '1' ? 'selected' : ''; ?>>Active</option>
                                              <option value="0" <?php echo $model->status == '0' ? 'selected' : ''; ?>>Inactive</option>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" id="add_pkg_btn"><?php echo $model->id ? 'Update' : 'Add'; ?></button>
                                <a href="{{ url(config('app.adminPrefix').'/homepage-component/index') }}">
                                    <button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button>
                                </a>
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
<script src="{{asset('public/assets/js/homepagecomponent/homepagecomponent.js')}}"></script>
<script>
    let page_name = '<?php echo $page_name; ?>'
</script>
<script type="text/javascript">
    CKEDITOR.replace('text', {
        filebrowserUploadUrl: "{{route('ckeditor.upload_home_page_image', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form',
        allowedContent: true,
        height: 320
    });

   /*  CKEDITOR.replace('text', {
        filebrowserUploadUrl: "{{route('ckeditor.upload_home_page_image', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('text', {
      fullPage: true,
      allowedContent: true,
      height: 320
    }); */
</script>
@endpush
