/** add  music cateogry form validation */
$("#addGroupForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        "name": {
            required: true,
        },
        "type": {
            required: true,
        },
        "image_shape": {
            required: true,
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "type": {
            required: "Please select type"
        },
        "image_shape": {
            required: "Please select shape",
        },
    },
    errorPlacement: function(error, element) {
        if ( element.is(":radio") ) {
            error.prependTo( element.parent().parent() );
        }
        else { // This is the default behavior of the script
            error.insertAfter( element );
        }
    },
    submitHandler: function(form)
    {
        form.submit();
    }
});

/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .group_delete', function () {
        var group_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#groupDeleteModel').on('show.bs.modal', function(e){
            $('#group_id').val(group_id);
            $('#message_delete').text(message);
        });
        $('#groupDeleteModel').modal('show');
    })

    $(document).on('click','#deletegroup', function(){
        var group_id = $('#group_id').val();
        $.ajax({
            url: origin + '/../delete/' + group_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                group_id: group_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#groupDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#groupDeleteModel').modal('hide')
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        });
    })

    $('#Tdatatable').on('click', '.active-inactive-link', function () {
        var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        toggleButton.trigger('click');
    });
    /** toggle active switch and show confirmation */
    $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
        var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var group_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#groupIsActiveModel').on('show.bs.modal', function(e){
            $('#group_id').val(group_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#groupIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#groupIsActive', function(){
        var group_id = $('#group_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "group_id": group_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#groupIsActiveModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                }, 5000);
            }
        })
    });

    $('#Tdatatable').on('click', 'tbody .toggle-is-approve-switch', function () {
        var approve = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
        var group_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#groupIsApproveModel').on('show.bs.modal', function(e){
            $('#group_id').val(group_id);
            $('#approve').val(approve);
            $('#messageApprove').text(message);
        });
        $('#groupIsApproveModel').modal('show');
    });
});
$(document).on('click','#search_group', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var status = $('#statusFilter').val();
    var type = $('#type').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    // console.log(startDate);
    DatatableInitiate(status,fromDate,toDate,type);
});


function DatatableInitiate(status='',startDate='',endDate='',type='') {
    var token = $('input[name="_token"]').val();
    var table = $('#Tdatatable').DataTable({
        language: {
            searchPlaceholder: "Search by Name..."
        },
        "bDestroy": true,
        "processing": true,
        "serverSide": true,
        "createdRow": function( row, data, dataIndex ) {
            $(row).addClass( data[0] );
        },
        "columnDefs": [
            // {
            //     targets : [-1],
            //     "orderable": false
            // },
            {
                targets: [0,5],
                className: "hide_column"
            },
            {
                targets: [1],
                className: "text-center opacity1",
            },
            {
                targets: [2],
                className: "text-left"
            },
            {
                targets: [1,3,4,5,6],
                className: "text-center"
            },
            {
                targets: [1,4,6],
                "orderable": false
            },
            {
                targets: [2,3,5],
                "orderable": true
            },
            // {
            //     targets: [3],
            //     className: "text-center",
            //     "orderable": true
            // },
            {
                targets: [6],
                className: "text-center", orderable: false, searchable: false
            }
        ],
        "order": [[2, "desc"]],
        "scrollX": true,
        "ajax": {
            url:  'list', // json datasource
            data:{
                _token : token,
                status : status,
                startDate:startDate,
                endDate:endDate,
                type:type,
            },
            error: function () {  // error handling
                $(".Tdatatable-error").html("");
                $("#Tdatatable").append('<tbody class="GroupDataList-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#Tdatatable_processing").css("display", "none");

            }
        },
        // "bStateSave": true,
        // "fnStateSave": function (oSettings, oData) {
        //     localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
        // },
        // "fnStateLoad": function (oSettings) {
        //     return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
        // }

    });
}
