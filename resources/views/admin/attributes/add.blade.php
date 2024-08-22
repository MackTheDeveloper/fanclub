@extends('admin.layouts.master')
<title>Attributes</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.css"
    rel="stylesheet">

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush

@section('content')
<div class="app-container body-tabs-shadow fixed-header fixed-sidebar app-theme-gray closed-sidebar">
    @include('admin.include.header')
    <div class="app-main">
        @include('admin.include.sidebar')
        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pr-2">
                                        <i class="lnr-cog opacity-6"></i>
                                    </span>
                                    <span class="d-inline-block">Attributes</span>
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
                                                <a href="javascript:void(0);">Attributes</a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a href="{{url(config('app.adminPrefix').'/attribute/list')}}">Attributes List</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Add Attribute
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
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a data-toggle="tab" href="#attribute_form" class="active nav-link">Attribute Form</a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="#attribute_types" class="nav-link">Attribute Types</a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="#category_tree" class="nav-link">Category Tree</a>
                            </li>
                        </ul>

                        <form id="attribute_create_form" class="col-md-10 mx-auto" method="post"
                            action="{{url(config('app.adminPrefix').'/attribute/addAttribute')}}">
                            @csrf
                            <input type="hidden" name="category_id" id="categoryId">
                            <input type="hidden" id="block_count" value="1">

                            <div class="tab-content">
                                <div class="tab-pane active" id="attribute_form" role="tabpanel">
                                    <div class="card-body">
                                        <h5 class="card-title">Create Attribute</h5>
                                        <div class="divider"></div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="internal_name">Display Name</label>
                                                    <span class="text-danger">*</span>
                                                    <div>
                                                        <input type="text" class="form-control" id="internal_name"
                                                            name="internal_name" value="{{old('internal_name')}}"
                                                            parsley-required="true">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <span class="text-danger">*</span>
                                                    <div>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                            value="{{old('name')}}" parsley-required="true">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="parent_id">Attribute Group
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div>
                                                        <select class="js-states browser-default select2 form-control"
                                                            name="parent_id" id="parent_id" parsley-required="true">
                                                            <option value="" disabled selected>Select</option>
                                                            @foreach($attribute_groups as $attribute_group)
                                                            <option value="{{ $attribute_group->id }}">
                                                                {{ $attribute_group->display_name}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="attribute_type_id">Attribute Type</label>
                                                    <div>
                                                        <select class="js-states browser-default select2 form-control"
                                                            name="attribute_type_id" id="attribute_type_id">
                                                            <option value="" disabled selected>Select</option>
                                                            @foreach($attribute_types as $attribute_type)
                                                            <option value="{{ $attribute_type->id }}">
                                                                {{ $attribute_type->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sort_order">Sort Order
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div>
                                                        <input type="number" class="form-control" id="sort_order"
                                                            name="sort_order" value="{{old('sort_order')}}"
                                                            parsley-required="true" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2 form-group">
                                                <label for="is_variant">Is Variant
                                                </label>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" id="is_variant" class="custom-control-input"
                                                        name="is_variant" value="1">
                                                    <label class="custom-control-label" for="is_variant"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 form-group">
                                                <label for="is_filterable">Is Filterable
                                                </label>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" id="is_filterable"
                                                        class="custom-control-input" name="is_filterable" value="1">
                                                    <label class="custom-control-label" for="is_filterable"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-2 offset-md-4">
                                                            <button type="submit" id="send"
                                                                class="btn btn-primary btn-shadow w-100">Add
                                                                Attribute</button>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="{{ url(config('app.adminPrefix').'/attribute/list') }}">
                                                                <button type="button"
                                                                    class="btn btn-light btn-shadow w-100" name="cancel"
                                                                    value="Cancel">Cancel</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="attribute_types" role="tabpanel">
                                    <div class="card-body">
                                        <h5 class="card-title">Attribute Options</h5>
                                        <div class="divider"></div>

                                        <div id="attr_type_block_1">
                                            <div class="row">
                                                <div class="col-md-4 form-group text-right">
                                                    <label for="display_name">Display Name
                                                        <span class="required">*</span>
                                                    </label>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <input type="text" class="form-control dispName required"
                                                        id="display_name" name="display_name[]" />

                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn att_type_1" id="add_more_type_1"
                                                        onclick="add_attr_type_block(1)">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="col-md-4 form-group text-right">
                                                    <label for="title">Title</label>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <input type="text" class="form-control" id="title" name="title[]" />
                                                </div>

                                                <div class="col-md-4 form-group text-right multi">
                                                    <label for="multicolor">Multicolor
                                                    </label>
                                                </div>
                                                <div class="col-md-6 form-group multi">
                                                    <input type="hidden" name="multicolor[1]" value="0" />
                                                    <input type="checkbox" id="multicolor" name="multicolor[1]"
                                                        value="1">
                                                </div>
                                            </div>
                                        </div>

                                        <div id="attr_type_add"></div>

                                        <div class="col-md-12 text-center">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-2 offset-md-4">
                                                        <button type="submit" id="send"
                                                            class="btn btn-primary btn-shadow w-100">Add Attribute</button>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="{{ url(config('app.adminPrefix').'/attribute/list') }}">
                                                            <button type="button" class="btn btn-light btn-shadow w-100"
                                                                name="cancel" value="Cancel">Cancel</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="category_tree" role="tabpanel">
                                    <div id="category_list_div"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.js"></script>
<script src="{{asset('public/assets/js/attribute/add_attr.js')}}"></script>
<script src="{{asset('public/assets/js/attribute/attribute.js')}}"></script>
<script src="{{asset('public/assets/js/attribute/category_list.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script>
    var page_name = '<?php echo $page_name; ?>'

</script>

<script>
    $("#attribute_create_form").validate({
        ignore: [], // ignore NOTHING
        rules: {
            "internal_name": {
                required: true,
            },
            "name": {
                required: true,
            },
            'sort_order': {
                required: true
            },
            'parent_id': {
                required: true
            },
            'display_name[]': {
                required: function () {
                    if ($('#attribute_type_id').val() == 6 || $('#attribute_type_id').val() == 7 || $(
                            '#attribute_type_id').val() == 8) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        messages: {
            "internal_name": {
                required: "Please enter display name"
            },
            "name": {
                required: "Please enter name",
            },
            'sort_order': {
                required: "Please enter sort order"
            },
            'parent_id': {
                required: 'Please select attribute group'
            },
            'display_name[]': {
                required: 'Please enter display name'
            }
        },
    });

</script>

@endpush
