@extends('admin.layouts.master')
@section('title','Products')
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
                                        <i class="fa pe-7s-cart"></i>
                                    </span>
                                    <span class="d-inline-block">Related Products - {{ $model->title }}</span>
                                    <input type="hidden" id="product_id" value="{{$model->id}}">
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
                                                <a href="{{ url(config('app.adminPrefix').'/product/index') }}">Products</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Related Product List
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm addRelatedProductsBtn" ><i class="fa fa-plus btn-icon-wrapper"> </i>Add Product</button>
                            </div>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="expand_collapse_filter"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm">
                                    <i aria-hidden="true" class="fa fa-filter"></i> Filter
                                </button></a>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card expand_filter" style="display:none;">
                    <div class="card-body">
                        <h5 class="card-title"><i aria-hidden="true" class="fa fa-filter"></i> Filter</h5>
                        <div>

                            {{ Form::open(array('url' => '','class'=>'form-inline','id'=>'','autocomplete'=>'off')) }}
                            @csrf
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group d-none">
                                <label for="filter_date" class="mr-sm-2">Select Date Range</label>
                                <input type="text" class="form-control" name="daterange" id="daterange" />
                            </div>
                            <!-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">From</label>
                                <input type="text" name="from_date_filter" placeholder=" -- From Date" class="form-control datepicker from_date_filter">
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">To</label>
                                <input type="text" name="to_date_filter" placeholder=" -- To Date" class="form-control datepicker to_date_filter">
                            </div> -->
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="category_id" class="mr-sm-2">Cateogry</label>
                                <?php echo Form::select('category_id', $productCategories, '', ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'category_id']); ?>
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="user_id" class="mr-sm-2">Professional</label>
                                <?php echo Form::select('user_id', $professionals, '', ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'user_id']); ?>
                            </div>
                            <button type="button" id="filter_products" class="btn btn-primary">Search</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table id="Tdatatable" class="display nowrap table table-hover table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th style="display: none">ID</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Professional</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
</div>
@endsection
@section('modals-content')
    <!-- Modal for activating deactivating template -->
    <div class="modal fade" id="productsIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="productsIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="products_id" id="products_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="productsIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="productsDeleteModel" tabindex="-1" role="dialog" aria-labelledby="productsDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="products_id" id="products_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteProducts">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Add Related Products Modal -->
    <div class="modal fade" id="addRelatedProductModel" tabindex="-1" role="dialog" aria-labelledby="productsDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- <form id="addRelatedProductsForm"> -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="productsDeleteModelLabel">Add Products</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <?php echo Form::select('related_product_id', $products, '', ['class' => 'form-control multiselect-dropdown', 'multiple'=>true,'id'=>'related_product_id']); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary" id="addRelatedProducts">Yes</button>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </div>
@endsection

<style>
    .hide_column {
        display: none;
    }
</style>
@push('scripts')
<script>
    let checkRecord = '';
</script>
<script src="{{asset('public/assets/js/products/products_related.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush