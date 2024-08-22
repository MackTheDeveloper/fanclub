@extends('admin.layouts.master')
<title><?php echo $model->id ? 'Edit fanclub Playlists | ' . config('app.name_show') : 'Add fanclub Playlists | ' . config('app.name_show'); ?></title>
<script type="text/javascript">
    var baseUrl = <?php echo json_encode($baseUrl); ?>;
    var type = <?php echo json_encode($model->type); ?>;
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
                                        <i class="fa pe-7s-music"></i>
                                    </span>
                                    <span class="d-inline-block">fanclub Playlists</span>
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
                                                <a href="javascript:void(0);" style="color: grey">fanclub Playlists</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/playlists/index')}}" style="color: grey">List</a>
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
                        <h5 class="card-title">fanclub Playlists INFORMATION</h5>
                        <?php
                        if ($model->id)
                            $actionUrl = url(config('app.adminPrefix').'/playlists/update', $model->id);
                        else
                            $actionUrl = url(config('app.adminPrefix').'/playlists/store');
                        ?>
                        <form id="addPlaylistForm" enctype="multipart/form-data" class="" method="post" action="{{$actionUrl}}">
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
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Dynamic Groups</label>
                                        <span class="text-danger">*</span>
                                          <select name="dynamic_group_id" id="dynamic_group_id" class="multiselect-dropdown form-control" >
                                              <option value="">Select Dynamic Groups</option>
                                              @if(isset($DynamicGroups))
                                              @foreach($DynamicGroups as $val)
                                              <option value="{{$val->id}}" <?php echo $model->dynamic_group_id == $val->id ? 'selected' : ''; ?>>{{$val->name}}</option>
                                              @endforeach
                                              @endif
                                          </select>
                                    </div>
                                    <div class="form-group dynamicGroupsLinkPlaylists"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="seo_title" class="font-weight-bold">Sort Order</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" placeholder="Enter Sort Order" value="{{$model->sort_order}}" />
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
                                <a href="{{ url(config('app.adminPrefix').'/playlists/index') }}">
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
<script src="{{asset('public/assets/js/playlists/playlist.js')}}"></script>
<script>
    let page_name = '<?php echo $page_name; ?>'
</script>
@endpush
