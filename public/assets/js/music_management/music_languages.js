/** add  music cateogry form validation */
$("#addMusicLanguageForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        name: {
            required: true,
        },
        image: {
            required: function() {
                var origin = window.location.href;
                if (origin.indexOf("edit") != -1)
                return false;
                else
                return true;
              },
        },
    },
    messages: {
        "name": {
            required: "Please enter name"
        },
        "image": {
            required: "Please add icon with size 800 X 580 px"
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
    $('#Tdatatable').on('click', 'tbody .music_languages_delete', function () {
        var music_languages_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#musicLanguagesDeleteModel').on('show.bs.modal', function(e){
            $('#music_languages_id').val(music_languages_id);
            $('#message_delete').text(message);
        });
        $('#musicLanguagesDeleteModel').modal('show');
    })

    $(document).on('click','#deletemusicLanguages', function(){
        var music_languages_id = $('#music_languages_id').val();
        $.ajax({
            url: origin + '/../delete/' + music_languages_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                music_languages_id: music_languages_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicLanguagesDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#musicLanguagesDeleteModel').modal('hide')
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
        var music_languages_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#musicLanguagesIsActiveModel').on('show.bs.modal', function(e){
            $('#music_languages_id').val(music_languages_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#musicLanguagesIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#musicLanguagesIsActive', function(){
        var music_languages_id = $('#music_languages_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "music_languages_id": music_languages_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicLanguagesIsActiveModel').modal('hide')
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
                searchPlaceholder: "Search by Name ..."
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
                    targets: [0,4],
                    className: "hide_column"
                },
                {
                    targets: [1],
                    className: "opacity1 text-center",
                    "orderable": false
                },
                {
                    targets: [2],
                    className: "text-left",
                 },
                // {
                //     targets: [2],
                //     className: "text-center",
                //     "render": function(data, type, row) {
                //         return '<img width="50" height="50" src="http://localhost/clubfan1/public/securefcbcontrol/music_language/'+data+'" />';
                //     },
                //     "orderable": true
                // },
                {
                    targets: [3],
                    className: "text-center",
                    "orderable": false
                },
                {
                    targets: [5],
                    className: "text-center", orderable: true, searchable: false
                }
            ],
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
