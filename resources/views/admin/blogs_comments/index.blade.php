@extends('admin.layouts.master')
@section('title','Blog Comments')
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
                                        <i class="fa pe-7s-browser"></i>
                                    </span>
                                    <span class="d-inline-block">Blog Comments</span>
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
                                                <a href="javascript:void(0);">Blog Comments</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                List <?php echo !empty($blogData['title']) ? '<b>- ' . strtoupper($blogData['title']) . '</b>' : ''; ?>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
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

                            {{ Form::open(array('url' => '','class'=>'form-inline','id'=>'addBlogForm','autocomplete'=>'off')) }}
                            @csrf
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="filter_date" class="mr-sm-2">Select Date Range</label>
                                <input type="text" class="form-control" name="daterange" id="daterange" />
                            </div>
                            <!-- <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">From</label>
                                <input type="text" name="from_date_filter" placeholder=" -- From Date" class="form-control datepicker from_date_filter">
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="" class="mr-sm-2">To</label>
                                <input type="text" name="to_date_filter" placeholder=" -- From Date" class="form-control datepicker to_date_filter">
                            </div> -->
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="blog_id" class="mr-sm-2">Blog</label>
                                <?php echo Form::select('blog_id', $blogs, $blogID, ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'blog_id']); ?>
                            </div>
                            <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                                <label for="user_id" class="mr-sm-2">User</label>
                                <?php echo Form::select('user_id', $users, '', ['class' => 'form-control multiselect-dropdown', 'placeholder' => 'Select ...', 'id' => 'user_id']); ?>
                            </div>
                            <button type="button" id="filter_blog_comments" class="btn btn-primary">Search</button>
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
                                    <th>User Name</th>
                                    <th>Image</th>
                                    <th>Email</th>
                                    <th>Comment</th>
                                    <th>Is Active</th>
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
    <div class="modal fade" id="blogCommentsIsActiveModel" tabindex="-1" role="dialog" aria-labelledby="blogCommentsIsActiveModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blogCommentsIsActiveModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="blog_comments_id" id="blog_comments_id">
                    <input type="hidden" name="status" id="status">
                    <p class="mb-0" id="message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="blogCommentsIsActive">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for delete template -->
    <div class="modal fade" id="blogCommentsDeleteModel" tabindex="-1" role="dialog" aria-labelledby="blogCommentsDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blogCommentsDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="blog_comment_id" id="blog_comment_id">
                    <p class="mb-0" id="message_delete"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteBlogComments">Yes</button>
                </div>
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
    let blogId = <?php echo !empty($blogID) ? $blogID : '0' ?>;
    let urlList = "{{url(config('app.adminPrefix').'/blog-comment/list')}}";
</script>
<script src="{{asset('public/assets/js/blog_comments/blog_comments.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.expand_collapse_filter').on('click', function() {
            $(".expand_filter").toggle();
        })
    })
</script>
@endpush