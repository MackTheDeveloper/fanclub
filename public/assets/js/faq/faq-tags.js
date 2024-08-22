/** add  music cateogry form validation */
$("#addMusicGenreForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        name: {
            required: true,
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
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
    $('#Tdatatable').on('click', 'tbody .music_genres_delete', function () {
        var music_genres_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#musicGenresDeleteModel').on('show.bs.modal', function(e){
            $('#music_genres_id').val(music_genres_id);
            $('#message_delete').text(message);
        });
        $('#musicGenresDeleteModel').modal('show');
    })

    $(document).on('click','#deletemusicGenres', function(){
        var music_genres_id = $('#music_genres_id').val();
        $.ajax({
            url: origin + '/../delete/' + music_genres_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                h_id: music_genres_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicGenresDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#musicGenresDeleteModel').modal('hide')
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
        var music_genres_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#musicGenresIsActiveModel').on('show.bs.modal', function(e){
            $('#music_genres_id').val(music_genres_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#musicGenresIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#musicGenresIsActive', function(){
        var music_genres_id = $('#music_genres_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "h_id": music_genres_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicGenresIsActiveModel').modal('hide')
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


function DatatableInitiate() {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Tag Name..."
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
                    targets: [0,3,4],
                    className: "hide_column"
                },
                {
                    targets: [1],
                    className: "text-center opacity1",
                },
                {
                    targets: [1,3],
                    className: "text-center",
                    "orderable": false
                },
                {
                    targets: [2,4],
                    className: "text-left",
                    "orderable": true
                },
                {
                    targets: [5],
                    className: "text-center",orderable: false, searchable: false

                },
            ],
            "order": [[5, "asc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: { },
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
