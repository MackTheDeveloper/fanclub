var origin   = window.location.href;
var reintialize_table;
var table;
$(document).ready(function() {
    DatatableInitiate();
    

    $(document).on('click','#search_role', function(){
        // var is_subscribed_filter = $("#is_subscribed_filter").val();
        // var status = $("#status").val();
        // var startDate = $('#daterange').data('daterangepicker').startDate;
        // var endDate = $('#daterange').data('daterangepicker').endDate;
        // var endDate = $('#daterange').data('daterangepicker').endDate;
        // fromDate = startDate.format('YYYY-MM-DD');
        // toDate = endDate.format('YYYY-MM-DD');
        var startDate = $('#daterange').data('daterangepicker').startDate;
        var endDate = $('#daterange').data('daterangepicker').endDate;
        fromDate = startDate.format('YYYY-MM-DD');
        toDate = endDate.format('YYYY-MM-DD');

        var location_group_id = $("#location_group_id").val();
        var category_id = $("#category_id").val();
        DatatableInitiate(fromDate,toDate,location_group_id,category_id);  
    })

    $('#user_list').on('click', 'tbody .user_delete', function () {
        var data_row = table.row($(this).closest('tr')).data();
        var id = data_row.id;
        var message = "Are you sure?";
        $('#deleteUserModel').on('show.bs.modal', function(e){
            $('#id').val(id);
            $('#delete_message').text(message);
        });
        $('#deleteUserModel').modal('show');
    })

    $(document).on('click', '#userDelete', function(){
        var id = $('#id').val();
        var is_deleted = $('#is_deleted').val();
        $.ajax({
            url: origin + '/../' + id + '/delete',
            method: "GET",
            data:{is_deleted: 1},
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

   
    $('#user_list').on('click', '.active-inactive-link', function () {
        var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        toggleButton.trigger('click');
    });
    $('#user_list').on('click', 'tbody .toggle-is-active-switch', function () {
        // alert($(this).attr('aria-pressed'));
        var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var data_row = table.row($(this).closest('tr')).data();
        if(data_row == undefined)
        {
            var data_row = reintialize_table.row($(this).closest('tr')).data();
        }
        var id = data_row.id;
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
            $('#id').val(id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#userIsActiveModel').modal('show');
    });

    $(document).on('click','#userIsActive', function(){
        var id = $('#id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activate-deactivate',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status":status,
                "id":id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#userIsActiveModel').modal('hide')
                    table.ajax.reload();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                    // location.reload();
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        })
    });

});
function DatatableInitiate(fromDate='',toDate='',location_group_id='',category_id = '') {

    var origin   = window.location.href;
    // var is_professional = $("#is_professional").val();
    // var status = $("#status").val();
    table = $('#user_list').DataTable({
        bDestroy: true,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            "url": origin,
            "type": "GET",
            "data": function (d) {
                d.fromDate = fromDate;
                d.toDate = toDate;
                d.category_id = category_id;
                d.location_group_id = location_group_id;
            }
        },
        // order: [[ 6, 'desc' ]],
        columns: [
            {data: 'group_name', name: 'group_name'},
            {data: 'category_name', name: 'category_name'},
            {data: 'sort_order', name: 'sort_order'},
            // {data: 'company_name', name: 'company_name'},
            // {data: 'location_hero', name: 'location_hero',orderable: false, searchable: false,},
            {data: 'company_name', name: 'company_name',
                render: function(data, type, row){
                    var output = "";
                    output += '<p><b> '+ (row.company_name?row.company_name:'Not Added')+'</b></p>';
                    output += '<p><b>Start Date :</b>'+ (row.ad_hero_start_date?row.ad_hero_start_date:'Not Added')+'</p>';
                    output += '<p><b>End Date :</b>'+ (row.ad_hero_end_date?row.ad_hero_end_date:'Not Added')+'</p>';
                    output += '<p><b>Cost :</b>'+ (row.ad_hero_cost?row.ad_hero_cost:'Not Added')+'</p>';
                    return output;
                }
            },
            {data: 'location_hero_1', name: 'location_hero_1',orderable: false, searchable: false,
                render: function(data, type, row){
                    var output = "";
                    output += '<p><b> '+ (row.location_hero_1?row.location_hero_1:'Not Added')+'</b></p>';
                    output += '<p><b>Start Date :</b>'+ (row.location_hero_1_start_date?row.location_hero_1_start_date:'Not Added')+'</p>';
                    output += '<p><b>End Date :</b>'+ (row.location_hero_1_end_date?row.location_hero_1_end_date:'Not Added')+'</p>';
                    output += '<p><b>Cost :</b>'+ (row.location_hero_1_cost?row.location_hero_1_cost:'Not Added')+'</p>';
                    return output;
                }
            },
            {data: 'location_hero_2', name: 'location_hero_2',orderable: false, searchable: false,
                render: function(data, type, row){
                    var output = "";
                    output += '<p><b> '+ (row.location_hero_2?row.location_hero_2:'Not Added')+'</b></p>';
                    output += '<p><b>Start Date :</b>'+ (row.location_hero_2_start_date?row.location_hero_2_start_date:'Not Added')+'</p>';
                    output += '<p><b>End Date :</b>'+ (row.location_hero_2_end_date?row.location_hero_2_end_date:'Not Added')+'</p>';
                    output += '<p><b>Cost :</b>'+ (row.location_hero_2_cost?row.location_hero_2_cost:'Not Added')+'</p>';
                    return output;
                }
            },
            {data: 'location_hero_3', name: 'location_hero_3',orderable: false, searchable: false,
                render: function(data, type, row){
                    var output = "";
                    output += '<p><b> '+ (row.location_hero_3?row.location_hero_3:'Not Added')+'</b></p>';
                    output += '<p><b>Start Date :</b>'+ (row.location_hero_3_start_date?row.location_hero_3_start_date:'Not Added')+'</p>';
                    output += '<p><b>End Date :</b>'+ (row.location_hero_3_end_date?row.location_hero_3_end_date:'Not Added')+'</p>';
                    output += '<p><b>Cost :</b>'+ (row.location_hero_3_cost?row.location_hero_3_cost:'Not Added')+'</p>';
                    return output;
                }
            },
            {data: 'location_hero_4', name: 'location_hero_4',orderable: false, searchable: false,
                render: function(data, type, row){
                    var output = "";
                    output += '<p><b> '+ (row.location_hero_4?row.location_hero_4:'Not Added')+'</b></p>';
                    output += '<p><b>Start Date :</b>'+ (row.location_hero_4_start_date?row.location_hero_4_start_date:'Not Added')+'</p>';
                    output += '<p><b>End Date :</b>'+ (row.location_hero_4_end_date?row.location_hero_4_end_date:'Not Added')+'</p>';
                    output += '<p><b>Cost :</b>'+ (row.location_hero_4_cost?row.location_hero_4_cost:'Not Added')+'</p>';
                    return output;
                }
            },
            {data: 'id', name: 'action', orderable: false, searchable: false, className: 'text-center',
                render: function(data, type, row)
                {
                    // console.log(row.id);
                    var output = "";
                    output += '<a href="'+origin+'/../edit/'+row.id+'"><i class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;'
                    // output += '<a href="javascript:void(0);" class="user_delete"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                    output += '<div class="d-inline-block dropdown">'
                                +'<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">'
                                    +'<span class="btn-icon-wrapper pr-2 opacity-7">'
                                        +'<i class="fa fa-cog fa-w-20"></i>'
                                    +'</span>'
                                +'</button>'
                                +'<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">'
                                    +'<ul class="nav flex-column">'
                                        +'<li class="nav-item">'
                                            +'<a class="nav-link user_delete" >Delete</a>'
                                        +'</li>'
                                    +'</ul>'
                                +'</div>'
                            +'</div>';
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