@extends('admin.layouts.master')
@section('title','Roles')
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
                                        <i class="fa fa-users opacity-6"></i>
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
                                            <li class="breadcrumb-item ">
                                                <a href="javascript:void(0);">Roles</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                List
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <!-- <a href="{{url(config('app.adminPrefix').'/user/role/add')}}"><button type="button" class="btn-shadow btn btn-info">
                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                        <i class="fa fa-plus fa-w-20"></i>
                                    </span>
                                    New Role
                                </button></a> -->
                                <a href="{{url(config('app.adminPrefix').'/user/role/add')}}"><button class="mb-2 mr-2 btn-icon btn-square btn btn-primary btn-sm"><i class="fa fa-plus btn-icon-wrapper"> </i>Add Role</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-left" width="300px">
                                        Role
                                    </th>
                                    <th class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($roles->count())
                                @foreach ($roles as $index => $role)
                                <tr class="odd gradeX">
                                    <td class="text-left">
                                        {{$role->role_title}}
                                    </td>
                                    <td class="text-center" style="max-width:100px; min-width:100px;">
                                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_role_assign_permission') === true || Auth::guard('admin')->user()->id == 1)
                                        @if($role->id != 1)
                                        <a href="{{ url(config('app.adminPrefix').'/role/permissions').'/'.$role->id}}"><i aria-hidden="true" class="fa fa-lock" style="margin-bottom: 10px;font-size: 16px;"></i></a>
                                        @endif
                                        @endif
                                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_role_edit') === true || Auth::guard('admin')->user()->id == 1)
                                        <a href="{{ url(config('app.adminPrefix').'/user/role/edit').'/'.$role->id}}"><i aria-hidden="true" class="fa fa-edit" style=" margin-left: 10px;margin-bottom: 10px;font-size: 16px;"></i></a>
                                        @endif
                                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_role_delete') === true || Auth::guard('admin')->user()->id == 1)
                                            @if($role->id != 1)
                                            <a href="javascript:void(0);" data-role_id="{{$role->id}}" class="delete_role"><i class="fa fa-trash" aria-hidden="true" style=" margin-left: 10px;margin-bottom: 10px;font-size: 16px;"> </i></a>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="2">No Roles Found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('admin.include.footer')
        </div>
    </div>
@endsection
@section('modals-content')
    <!-- Modal Start -->
    <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="howItWorksDeleteModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="howItWorksDeleteModelLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="role_id" id="role_id">
                    <p class="mb-0" id="delete_message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="deleteRole">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Over -->
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete_role').click(function() {
            var role_id = $(this).attr('data-role_id');
            var message = "Are you sure?";
            $('#deleteModel').on('show.bs.modal', function(e) {
                $('#role_id').val(role_id);
                $('#delete_message').text(message);
            });
            $('#deleteModel').modal('show');
        })

        $('#deleteRole').on('click', function() {
            var role_id = $('#role_id').val();
            $.ajax({
                url: "{{url(config('app.adminPrefix').'/user/role/delete')}}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "role_id": role_id
                },
                success: function(response) {
                    if (response.status == 'true') {
                        // $('#deleteRoleModel').modal('hide');
                        // toastr.clear();
                        // toastr.options.closeButton = true;
                        // toastr.options.timeOut = 0;
                        // toastr.success('Role has been deleted successfully!');
                        // setTimeout(function(){
                        location.reload();
                        // }, 3000);
                    } else {
                        // $('#deleteRoleModel').modal('hide');
                        // toastr.clear();
                        // toastr.options.closeButton = true;
                        // toastr.options.timeOut = 0;
                        // toastr.success('Role has not been deleted successfully!');
                        // setTimeout(function(){
                        window.location.href = '/securefcbcontrol/user/role/list';
                        // }, 3000);
                    }
                }
            })
        })
    })
</script>
@endpush
