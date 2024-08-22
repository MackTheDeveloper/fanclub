/** add  music cateogry form validation */
$("#addMusicCategoryForm").validate({
    ignore: [], // ignore NOTHING
    rules: {
        name: {
            required: true,
        },
        slug: {
            required: true,
        },
        sortOrder: {
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
        "slug": {
            required: "Please enter slug"
        },
        "sortOrder": {
            required: "Please enter sort Order"
        },
        "image":{
            required: "Please add icon with size 800 X 580 px"
        }
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
    $('#Tdatatable').on('click', 'tbody .music_categories_delete', function () {
        var music_categories_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#musicCategoriesDeleteModel').on('show.bs.modal', function(e){
            $('#music_categories_id').val(music_categories_id);
            $('#message_delete').text(message);
        });
        $('#musicCategoriesDeleteModel').modal('show');
    })

    $(document).on('click','#deletemusicCategories', function(){
        var music_categories_id = $('#music_categories_id').val();
        $.ajax({
            url: origin + '/../delete/' + music_categories_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                music_categories_id: music_categories_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicCategoriesDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#musicCategoriesDeleteModel').modal('hide')
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
        var music_categories_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        if($(this).attr('aria-pressed') == 'false')
        {
            $(this).addClass('active');
        }
        if($(this).attr('aria-pressed') == 'true')
        {
            $(this).removeClass('active');
        }
        $('#musicCategoriesIsActiveModel').on('show.bs.modal', function(e){
            $('#music_categories_id').val(music_categories_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#musicCategoriesIsActiveModel').modal('show');
    });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#musicCategoriesIsActive', function(){
        var music_categories_id = $('#music_categories_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "music_categories_id": music_categories_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicCategoriesIsActiveModel').modal('hide')
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
                searchPlaceholder: "Search by Name ... "
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
