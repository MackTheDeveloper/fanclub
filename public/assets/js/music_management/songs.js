/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    var artist = $('#artist').val();
    DatatableInitiate('','','',artist);

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .song_delete', function () {
        var song_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#songDeleteModel').on('show.bs.modal', function(e){
            $('#song_id').val(song_id);
            $('#message_delete').text(message);
        });
        $('#songDeleteModel').modal('show');
    })

    $(document).on('click','#deletesong', function(){
        var song_id = $('#song_id').val();
        $.ajax({
            url: origin + '/../delete/' + song_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                song_id: song_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#songDeleteModel').modal('hide')
                    // DatatableInitiate();
                    DatatableInitiateWithFilter();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#songDeleteModel').modal('hide')
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
                "music_genres_id": music_genres_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#musicGenresIsActiveModel').modal('hide')
                    // DatatableInitiate();
                    DatatableInitiateWithFilter();
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
function DatatableInitiateWithFilter(){
    var category = $("#category").val();
    var genre = $("#genre").val();
    var language = $("#language").val();
    var artist = $("#artist").val();
    DatatableInitiate(category, genre, language, artist);
}
// filter_song
$(document).on('click','#filter_song',function(){
    DatatableInitiateWithFilter();
});

// song comments
$(document).on('click','.showCommentList',function(){
    var songId = $(this).data('id');
    // alert(songId);
    $('#clickSongId').val(songId);
    $('#postSongCommentList').submit();

});

function DatatableInitiate(category='',genre='',language='',artist='') {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Song Name, Artist Name ..."
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
                    targets: [0,5,8,9],
                    className: "hide_column"
                },
                {
                    targets: [1],
                    className: "opacity1 text-center"
                },
                {
                    targets: [1,4,5,6,10,11,12,13,14],
                    className: "text-center"
                },
                {
                    targets: [2,3,7,8,9],
                    className: "text-left"
                },
                {
                    targets: [2,3,5,10,11,12,13,14],
                    "orderable": true
                },
                {
                    targets: [1,4,6,7,8,9],
                    "orderable": false
                },
                {
                    targets: [1],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[11, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: {
                    category:category,
                    genre:genre,
                    language:language,
                    artist:artist
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
}
