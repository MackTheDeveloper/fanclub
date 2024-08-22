var origin   = window.location.href;
var reintialize_table;
var table;
$(document).ready(function() {
    DatatableInitiate();


    $(document).on('click','#search_role', function(){
        var is_subscribed_filter = $("#is_subscribed_filter").val();
        var status = $("#status").val();
        var startDate = $('#daterange').data('daterangepicker').startDate;
        var endDate = $('#daterange').data('daterangepicker').endDate;
        fromDate = startDate.format('YYYY-MM-DD');
        toDate = endDate.format('YYYY-MM-DD');
         var categoryId = $('#category_id').val();
         var profCategoryId = $('#professional_category_id').val();

        DatatableInitiate(fromDate, toDate,status,is_subscribed_filter,categoryId,profCategoryId);
    })

    $('#user_list').on('click', 'tbody .user_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();
        var is_deleted = data_row.is_deleted;
        var user_id = data_row.id;
        var message = "Are you sure?";
        $('#deleteUserModel').on('show.bs.modal', function(e){
            $('#user_id').val(user_id);
            $('#is_deleted').val(is_deleted);
            $('#delete_message').text(message);
        });
        $('#deleteUserModel').modal('show');
    })

    $(document).on('click', '#userDelete', function(){
        var user_id = $('#user_id').val();
        var is_deleted = $('#is_deleted').val();
        $.ajax({
            url: origin + '/../' + user_id + '/delete',
            method: "GET",
            data:{is_deleted: is_deleted},
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#deleteUserModel').modal('hide')
                    table.ajax.reload();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success("User deleted successfully!");
                    // window.location.href = '/securefcbcontrol/user/list';
                }
                else
                {
                    $('#deleteUserModel').modal('hide')
                    // table.ajax.reload();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success("Something went wrong!");
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        });
    })

    // $('#user_list').on('click', 'tbody .user_delete', function () {
    //     var data_row = table.row($(this).closest('tr')).data();
    //     if(data_row == undefined)
    //     {
    //         var data_row = reintialize_table.row($(this).closest('tr')).data();
    //     }
    //     var is_deleted = data_row.is_deleted;
    //     var user_id = data_row.id;
    //     if(confirm("Are you sure you want to delete this user ?"))
    //     {
    //         // alert('delete');
    //         $.ajax({
    //             url: origin + '/../' + user_id + '/delete',
    //             method: "GET",
    //             data:{is_deleted: is_deleted},
    //             success: function(response)
    //             {
    //                 // console.log(response);
    //                 if(response.status == 'true')
    //                 {
    //                     window.location.href = './list';
    //                 }
    //             }
    //         });
    //     }
    // })
    $('#user_list').on('click', '.active-inactive-link', function () {
        var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        toggleButton.trigger('click');

    });
    $('#user_list').on('click', 'tbody .toggle-is-active-switch', function () {
        // alert($(this).attr('aria-pressed'));
        var is_active = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var data_row = table.row($(this).closest('tr')).data();
        if(data_row == undefined)
        {
            var data_row = reintialize_table.row($(this).closest('tr')).data();
        }
        var user_id = data_row.id;
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?": "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#userIsActiveModel').on('show.bs.modal', function(e){
            $('#user_id').val(user_id);
            $('#is_active').val(is_active);
            $('#message').text(message);
        });
        $('#userIsActiveModel').modal('show');
    });
    $('#user_list').on('click', '.subscribe-unsubscribe-link', function () {
        var toggleButton = $(this).closest('tr').find('.toggle-is-subscribed-switch');
        toggleButton.trigger('click');

    });
    $('#user_list').on('click', 'tbody .toggle-is-subscribed-switch', function () {
        // alert($(this).attr('aria-pressed'));
        var is_subscribed = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var data_row = table.row($(this).closest('tr')).data();
        if(data_row == undefined){
            var data_row = reintialize_table.row($(this).closest('tr')).data();
        }
        var user_id = data_row.id;
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?": "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false'){
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true'){
            $(this).removeClass('active');
        }
        $('#userIsSubscribedModel').on('show.bs.modal', function(e){
            $('#user_id').val(user_id);
            $('#is_subscribed').val(is_subscribed);
            $('#message2').text(message);
        });
        $('#userIsSubscribedModel').modal('show');
    });

    $(document).on('click','#userIsActive', function(){
        var user_id = $('#user_id').val();
        var is_active = $('#is_active').val();
        $.ajax({
            url: origin + '/../activate-deactivate',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "is_active":is_active,
                "user_id":user_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    // $('#userIsActiveModel').modal('hide')
                    // table.ajax.reload();
                    // toastr.clear();
                    // toastr.options.closeButton = true;
                    // toastr.options.timeOut = 0;
                    // toastr.success(response.msg);
                    location.reload();
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        })
    });
    $(document).on('click','#userIsSubscribed', function(){
        var user_id = $('#user_id').val();
        var is_subscribed = $('#is_subscribed').val();
        $.ajax({
            url: origin + '/../subscribe-unsubscribe',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "is_subscribed":is_subscribed,
                "user_id":user_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    // $('#userIsActiveModel').modal('hide')
                    // table.ajax.reload();
                    // toastr.clear();
                    // toastr.options.closeButton = true;
                    // toastr.options.timeOut = 0;
                    // toastr.success(response.msg);
                    location.reload();
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        })
    });

});
function DatatableInitiate(fromDate = '', toDate = '',status = '',is_subscribed = '',categoryId='',profCategoryId='') {

    var origin   = window.location.href;
    // var is_professional = $("#is_professional").val();
    // var status = $("#status").val();
    table = $('#user_list').DataTable({
        bDestroy: true,
        processing: true,
        serverSide: true,
        'scrollX': true,
        ajax: {
            "url": origin,
            "type": "GET",
            "data": function (d) {
                d.fromDate = fromDate;
                d.toDate = toDate;
                d.is_subscribed = is_subscribed;
                d.status = status;
                d.categoryId = categoryId;
                d.profCategoryId = profCategoryId;
            }
        },
        order: [[ 7, 'desc' ]],
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'lastname', name: 'lastname'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'productCategories', name: 'productCategories', searchable: false, orderable: false},
            {data: 'address', name: 'address', orderable: false},
            {data: 'user_created_at', name: 'user_created_at',className: 'text-center'},
            {
                data: 'is_subscribed', name: 'is_subscribed', orderable: false, searchable: false, className: 'text-center',
                render: function (data, type, full, meta){
                    var output = "";
                    if(data == 1){
                        output += '<div class="row">'
                        +'<div class="col-sm-12">'
                        +'<button type="button" class="btn btn-sm btn-toggle active toggle-is-subscribed-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'
                    }else{
                        output += '<div class="row">'
                        +'<div class="col-sm-12">'
                        +'<button type="button" class="btn btn-sm  btn-toggle toggle-is-subscribed-switch" data-toggle="button" aria-pressed="false" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'
                    }
                    return output;
                },
            },
            {data: 'is_active', name: 'is_active', orderable: false, searchable: false, className: 'text-center',
                render: function (data, type, full, meta)
                {
                    var output = "";
                    if(data == 1)
                    {
                        output += '<div class="row">'
                        +'<div class="col-sm-12">'
                        +'<button type="button" class="btn btn-sm btn-toggle active toggle-is-active-switch" data-toggle="button" aria-pressed="true" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'
                    }
                    else
                    {
                        output += '<div class="row">'
                        +'<div class="col-sm-12">'
                        +'<button type="button" class="btn btn-sm btn-toggle toggle-is-active-switch" data-toggle="button" aria-pressed="false" autocomplete="off">'
                        +'<div class="handle"></div>'
                        +'</button>'
                        +'</div>'
                    }
                    return output;
                },
                // className: 'text-center',
            },
            // {data: 'is_deleted', name: 'is_deleted',
            //     render: function (data)
            //     {
            //         var output = "";
            //         if(data == 1)
            //         {
            //             output += '<div class="mb-2 mr-2 badge badge-focus">Yes</div>';
            //         }
            //         else
            //         {
            //             output += '<div class="mb-2 mr-2 badge badge-focus">No</div>';
            //         }
            //         return output;
            //     },
            //     // className: 'text-center',
            // },
            {data: 'id', name: 'action', orderable: false, searchable: false, className: 'text-center',
                render: function(data, type, row)
                {
                    // console.log(row.id);
                    // var output = "";
                    // output += '<a href="'+origin+'/../edit/'+row.id+'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    // output += '<a ><i class="fa fa-list" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    // output += '<a href="javascript:void(0);" class="user_delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    // return output;
                    var output = '';
                    output += '<a href="'+origin+'/../edit/'+row.id+'"><i style="color: grey" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    output += '<div class="d-inline-block dropdown">'
                                +'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                                    +'<span class="btn-icon-wrapper pr-2 opacity-7">'
                                        +'<i class="fa fa-cog fa-w-20"></i>'
                                    +'</span>'
                                +'</button>'
                                +'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                                    +'<ul class="nav flex-column">'
                                        +'<li class="nav-item">'
                                            +'<a class="nav-link subscribe-unsubscribe-link" >Mark as '+((row.is_subscribed == 1)?'Unsubscribe':'Subscribe')+'</a>'
                                        +'</li>'
                                        +'<li class="nav-item">'
                                            +'<a class="nav-link active-inactive-link" >Mark as '+((row.is_active == 1)?'Inactive':'Active')+'</a>'
                                        +'</li>'
                                        +'<li class="nav-item">'
                                            +'<a class="nav-link" href="'+origin+'/../designs/'+row.id+'" >Manage Designs</a>'
                                        +'</li>'
                                        +'<li class="nav-item">'
                                            +'<a class="nav-link user_delete" >Delete</a>'
                                        +'</li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>';
                    // output += '<a href="javascript:void(0);" class="user_delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    return output;
                },
                // className: 'text-center',
            },
        ],
        fnDrawCallback: function() {
            $('.toggle-is-deleted').bootstrapToggle();
        },
        "bStateSave": true,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        },
        "fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        }
    });
}
