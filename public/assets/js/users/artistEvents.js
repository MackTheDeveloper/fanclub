/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    // DatatableInitiate();

    /** delete music cateogry */
    // $('#Tdatatable').on('click', 'tbody .fan_delete', function () {
    //     alert('asdadad')
    //     var fan_playlist_id = $(this).data('id');
    //     var message = "Are you sure ?";
    //     console.log(message);
    //     $('#fanPlaylistDeleteModel').on('show.bs.modal', function(e){
    //         $('#fan_playlist_id').val(fan_playlist_id);
    //         $('#message_delete').text(message);
    //     });
    //     $('#fanPlaylistDeleteModel').modal('show');
    // })
    $(document).on('click', 'tbody .fan_delete', function () {
        var fan_playlist_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#fanPlaylistDeleteModel').on('show.bs.modal', function(e){
            $('#fan_playlist_id').val(fan_playlist_id);
            $('#message_delete').text(message);
        });
        $('#fanPlaylistDeleteModel').modal('show');
    })

    $(document).on('click','#deletefanPlaylist', function(){
        var fan_playlist_id = $('#fan_playlist_id').val();
        $.ajax({
            url: origin + '/../delete/' + fan_playlist_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                fan_playlist_id: fan_playlist_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#fanPlaylistDeleteModel').modal('hide')
                    // DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#fanPlaylistDeleteModel').modal('hide')
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                    window.location.reload();
                }, 500);
            }
        });
    })


    /** toggle active switch and show confirmation */
    // $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
    //     var status = ($(this).attr('aria-pressed')) ? 0 : 1;
    //     var fan_playlist_id = $(this).data('id');
    //     var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
    //     if($(this).attr('aria-pressed') == 'false')
    //     {
    //         $(this).addClass('active');
    //     }
    //     if($(this).attr('aria-pressed') == 'true')
    //     {
    //         $(this).removeClass('active');
    //     }
    //     $('#fanPlaylistIsActiveModel').on('show.bs.modal', function(e){
    //         $('#fan_playlist_id').val(fan_playlist_id);
    //         $('#status').val(status);
    //         $('#message').text(message);
    //     });
    //     $('#fanPlaylistIsActiveModel').modal('show');
    // });



})

$(document).on('click','#search_fan', function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    var status = $('#is_active').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    // console.log(startDate);
    // DatatableInitiate(status,fromDate,toDate);
});

// $('#Tdatatable').dataTable( {
//     language: {
//         searchPlaceholder: "Search By Playlist Name... "
//     },
// } );

// function DatatableInitiate(status='',startDate='',endDate='') {
//     var token = $('input[name="_token"]').val();
//     table = $('#Tdatatable').DataTable({
//         language: {
//             searchPlaceholder: "Search By Playlist Name ..."
//         },
//         "bDestroy": true,
//         "processing": true,
//         "serverSide": true,
//         "columnDefs": [
//             // {
//             //     targets : [-1],
//             //     "orderable": false
//             // },
//             {
//                 targets: [0],
//                 className: "hide_column"
//             },
//             {
//                 targets: [1],
//                 className: "opacity1 text-center"
//             },
//             {
//                 targets: [4,5,7,8],
//                 className: "text-center",
//                 "orderable": false
//             },
//             {
//                 targets: [2,3,6,9],
//                 className: "text-center",
//                 "orderable": true
//             },
//             // {
//             //     targets: [3],
//             //     className: "text-center",
//             //     "orderable": true
//             // },
//             {
//                 targets: [10],
//                 className: "text-center", orderable: false, searchable: false
//             }
//         ],
//         "order": [[9, "asc"]],
//         "scrollX": true,
//         "ajax": {
//             url: "list", // json datasource
//             data:{
//                 _token : token,
//                 is_active:status,
//                 startDate:startDate,
//                 endDate:endDate,
//             },
//             error: function () {  // error handling
//                 $(".Tdatatable-error").html("");
//                 $("#Tdatatable").append('<tbody class="Tdatatable-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
//                 $("#Tdatatable_processing").css("display", "none");

//             }
//         },
//         // "bStateSave": true,
//         // "fnStateSave": function (oSettings, oData) {
//         //     localStorage.setItem( 'DataTables_'+window.location.pathname, JSON.stringify(oData) );
//         // },
//         // "fnStateLoad": function (oSettings) {
//         //     return JSON.parse( localStorage.getItem('DataTables_'+window.location.pathname) );
//         // }
//     });
// }
