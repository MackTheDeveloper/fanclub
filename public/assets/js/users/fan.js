/** add  music cateogry form validation */
$("#addFanForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        firstname: {
            required: true,
        },
        lastname: {
            required: true,
        },
        email: {
            required: true,
        },
        phone: {
            required: true,
        },
        address: {
            required: true,
        },
        country: {
            required: true,
        },
    },
    messages: {
        "firstname": {
            required: "Please enter firstname"
        },
        "lastname": {
            required: "Please enter lastname"
        },
        "email": {
            required: "Please enter email"
        },
        "phone": {
            required: "Please enter phone"
        },
        "address": {
            required: "Please enter address"
        },
        "country": {
            required: "Please enter country"
        },
    },
    errorPlacement: function (error, element)
    {
        error.insertAfter(element)
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
    $('#Tdatatable').on('click', 'tbody .fan_delete', function () {
        var fan_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#fanDeleteModel').on('show.bs.modal', function(e){
            $('#fan_id').val(fan_id);
            $('#message_delete').text(message);
        });
        $('#fanDeleteModel').modal('show');
    })

    $(document).on('click','#deletefan', function(){
        var fan_id = $('#fan_id').val();
        $.ajax({
            url: origin + '/../delete/' + fan_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                fan_id: fan_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#fanDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#fanDeleteModel').modal('hide')
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
        var fan_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#fanIsActiveModel').on('show.bs.modal', function(e){
            $('#fan_id').val(fan_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#fanIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#fanIsActive', function(){
        var fan_id = $('#fan_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "fan_id": fan_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#fanIsActiveModel').modal('hide')
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
})

$(document).on('click','#search_fan', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var status = $('#is_active').val();
    var subscription = $('#subscription_filter').val();
    var country = $('#country_filter').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    // console.log(startDate);
    $('#exportFans #startDate').val(fromDate);
    $('#exportFans #endDate').val(toDate);
    $('#exportFans #status').val(status);
    $('#exportFans #subscription').val(subscription);
    $('#exportFans #country').val(country);
    // var search = $('.dataTables_filter input[type="search"]').val();
    // $('#exportFans #search').val(search);
    DatatableInitiate(status,fromDate,toDate,subscription,country);
});

$('#Tdatatable').on('search.dt', function() {
    var value = $('.dataTables_filter input').val();
    $('#exportFans #search').val(value);
}); 
function DatatableInitiate(status='',startDate='',endDate='',subscription='',country='') {
    var token = $('input[name="_token"]').val();
    table = $('#Tdatatable').DataTable({
        buttons: [
            {
                extend: 'csv',
            }
        ],
        language: {
            searchPlaceholder: "Search by Name, Email..."
        },
        "search": {
            "search": (dashboardSearch) ? dashboardSearch : ''
        },
        searching: false,
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
                targets: [0,11],
                className: "hide_column"
            }, 
            {
                targets: [1],
                className: "text-center opacity1"
            },
            // {
            //     targets: [9],
            //     className: "hide_column"
            // }, 
            {
                targets: [3,4,5,6,7],
                className: "text-left",
            },
            {
                targets: [1,2,8,9],
                className: "text-center",
            },
            {
                targets: [1,2,5,6,8],
                "orderable": false
            },
            {
                targets: [3,4,7],
                "orderable": true
            },
            // {
            //     targets: [3],
            //     className: "text-center",
            //     "orderable": true
            // },
            {
                targets: [3],
                className: "text-center", orderable: false, searchable: false
            }
        ],
        "order": [[9, "asc"]],
        "scrollX": true,
        "ajax": {
            url: "list", // json datasource
            data:{
                _token : token,
                is_active:status,
                startDate:startDate,
                endDate:endDate,
                subscription:subscription,
                country:country,
            },
            error: function () {  // error handling
                $(".Tdatatable-error").html("");
                $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
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
    $("#export").on("click", function() {
        table.button( '.buttons-csv' ).trigger();
    });
}
