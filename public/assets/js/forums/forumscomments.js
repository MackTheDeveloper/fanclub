/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .forum_comment_delete', function () {
        var forum_comment_id = $(this).data('id');
        var message = "Are you sure ?";
        $('#forumCommentDeleteModel').on('show.bs.modal', function(e){
            $('#forum_comment_id').val(forum_comment_id);
            $('#message_delete').text(message);
        });
        $('#forumCommentDeleteModel').modal('show');
    })

    $(document).on('click','#deleteforumComment', function(){
        var forum_comment_id = $('#forum_comment_id').val();
        $.ajax({
            url: origin + '/../delete/' + forum_comment_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                forum_comment_id: forum_comment_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#forumCommentDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#forumDeleteModel').modal('hide')
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
        // var toggleButton = $(this).closest('tr').find('.toggle-is-active-switch');
        // toggleButton.trigger('click');
        var status = $(this).data('status');
        var forum_id = $(this).data('id');
        var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
        $('#forumIsActiveModel').on('show.bs.modal', function(e){
            $('#forum_id').val(forum_id);
            $('#status').val(status);
            $('#message').text(message);
        });
        $('#forumIsActiveModel').modal('show');
    });
    /** toggle active switch and show confirmation */
    // $('#Tdatatable').on('click', 'tbody .toggle-is-active-switch', function () {
    //     var status = ($(this).attr('aria-pressed') === 'true') ? 0 : 1;
    //     var forum_id = $(this).data('id');
    //     var message = ($(this).attr('aria-pressed') === 'true') ? "Are you sure ?" : "Are you sure ?";
    //     if($(this).attr('aria-pressed') == 'false')
    //     {
    //         $(this).addClass('active');
    //     }
    //     if($(this).attr('aria-pressed') == 'true')
    //     {
    //         $(this).removeClass('active');
    //     }
    //     $('#forumIsActiveModel').on('show.bs.modal', function(e){
    //         $('#forum_id').val(forum_id);
    //         $('#status').val(status);
    //         $('#message').text(message);
    //     });
    //     $('#forumIsActiveModel').modal('show');
    // });


    /** Activate or deactivate music cateogry */
    $(document).on('click','#forumIsActive', function(){
        var forum_id = $('#forum_id').val();
        var status = $('#status').val();
        $.ajax({
            url: origin + '/../activeInactive',
            method: "POST",
            data:{
                "_token": $('#token').val(),
                "status": status,
                "forum_id": forum_id
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#forumIsActiveModel').modal('hide')
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

// filter_song
$(document).on('click','#filter_forum_comment',function(){
    var startDate = $('.filter_form #daterange').data('daterangepicker').startDate;
    var endDate = $('.filter_form #daterange').data('daterangepicker').endDate;
    // var createdBy = $('.filter_form #createdBy').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    DatatableInitiate(fromDate,toDate);
    // DatatableInitiate(fromDate,toDate,createdBy);
});

// song comments
$(document).on('click','.showCommentList',function(){
    var songId = $(this).data('id');
    // alert(songId);
    $('#clickSongId').val(songId);
    $('#postSongCommentList').submit();

});

function DatatableInitiate(fromDate='',toDate='',createdBy='') {
    var forum_id = $('#forum_id').val();
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Comments, Created By..."
            },
            "bDestroy": true,
            "processing": true,
            "serverSide": true,
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
                    targets: [1,4],
                    className: "text-center"
                },
                {
                    targets: [2,3],
                    className: "text-left"
                },
                {
                    targets: [1,2,3,4],
                    "orderable": true
                },
                {
                    targets: [5],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[3, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list/"+forum_id, // json datasource
                data: {
                    status:status,
                    startDate:fromDate,
                    endDate:toDate,
                    created_by:createdBy
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
