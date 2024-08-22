
$("#addMusicCategoryForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        image: {
            required: {
                depends: function(element) {
                    var condn1 = 'icon' == $('input[type="radio"]:checked').val();
                    var origin = window.location.href;
                    var condn2 = origin.indexOf("edit") != -1 ? false : true;
                    return condn1 && condn2;
                },
                extension: "jpg|jpeg|png|ico|bmp",
            }
        },
        comment: {
            required: {
                depends: function(element) {
                    return 'comment' == $('input[type="radio"]:checked').val();
                }
            }
        }

    },
    messages:
        {
            image:{
                required: "Please upload file !!",
                extension: "Please upload file in these format only (jpg, jpeg, png, ico, bmp)"
            },
            comment:{
                required: "Please add comment !!"
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

    $('.expand_collapse_filter').on('click', function() {
        $(".expand_filter").toggle();
    })

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .how_it_works_delete', function () {
        var how_it_works_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#howItWorksDeleteModel').on('show.bs.modal', function(e){
            $('#how_it_works_id').val(how_it_works_id);
            $('#message_delete').text(message);
        });
        $('#howItWorksDeleteModel').modal('show');
    })

    $(document).on('click','#deletehowItWorks', function(){
        var id = $('#how_it_works_id').val();
        $.ajax({
            url: origin + '/../delete/' + how_it_works_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                id: id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#howItWorksDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#howItWorksDeleteModel').modal('hide')
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
        var how_it_works_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#howItWorksIsActiveModel').on('show.bs.modal', function(e){
            $('#how_it_works_id').val(how_it_works_id);
            $('#is_active').val(status);
            $('#message').text(message);
        });
        $('#howItWorksIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#howItWorksIsActive', function(){
        var e_id = $('#how_it_works_id').val();
        var status = $('#is_active').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "id": e_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#howItWorksIsActiveModel').modal('hide')
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

$(document).on('click','#search_type', function(){
    var type = $('#type').val();
    // $('#exportArtist #type').val(type);
    DatatableInitiate(type);
});

function DatatableInitiate(type='') {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Type..."
            },
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
            "createdRow": function( row, data, dataIndex ) {
                $(row).addClass( data[0] );
            },
            /* 'stateSave': true,
            stateSaveParams: function (settings, data) {
                delete data.order;
            }, */
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
                    targets: [4],
                    className: "text-left",
                },
                {
                    targets: [1,2,3,4],
                    className: "text-center",
                },
                {
                    targets: [5,6],
                    className: "text-center",orderable: true
                },
                {
                    targets: [1,6],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[5, "asc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: {type:type},
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

}
$(document).ready(function () {
    var data = $('input[type="radio"]:checked').val();
    if (data == 'comment')
    {
        $('#imageId').hide();

    }
    if (data == 'icon')
    {
        $('#commentId').hide();

    }
});


$('input[type="radio"]').on('change', function(e)
{
    if ($(this).val() == 'icon') {
        $('#imageId').show();
        $('#commentId').hide();
    }
    else if ($(this).val() == 'comment') {
        $('#commentId').show();
        $('#imageId').hide();
    }
});



