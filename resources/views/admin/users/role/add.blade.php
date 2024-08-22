@extends('admin.layouts.master')
@section('title','Add Role')
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
                                        <i class="lnr-users opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Roles</span>
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
                                                <a href="javascript:void(0);" >Roles</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/user/role/list')}}" >List</a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">
                                              <a >Add</a>
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
                        <h5 class="card-title">Role Information</h5>
                        <!-- @if(Session::has('msg'))
                            <div class="alert {{(Session::get('alert-class') == true) ? 'alert-success' : 'alert-danger'}} alert-dismissible fade show" role="alert">
                                {{ Session::get('msg') }}
                                <button type="button" class="close session_error" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif                       -->
                        <form id="roleForm" class="" method="post" action="{{url(config('app.adminPrefix').'/user/role/add')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="role_title">Role Title</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <input type="text" class="form-control" id="role_title" name="role_title" placeholder="Role Title" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold" for="role_type">Role Type</label>
                                        <span class="text-danger">*</span>
                                        <div>
                                            <select name="role_type" id="role_type" class="multiselect-dropdown form-control">
                                                <optgroup label="Select Role">
                                                    <option value="admin" style="color: grey">Admin</option>
                                                </optgroup>
                                                <!-- <option value="photographer">Photographer</option> -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="add_role" value="add_role">Add</button>
                                <a href="{{url(config('app.adminPrefix').'/user/role/list')}}"><button type="button" class="btn btn-light" name="cancel" value="Cancel">Cancel</button></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection
