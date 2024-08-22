/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    DatatableInitiate();

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .forum_delete', function () {
        var forum_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#forumDeleteModel').on('show.bs.modal', function(e){
            $('#forum_id').val(forum_id);
            $('#message_delete').text(message);
        });
        $('#forumDeleteModel').modal('show');              
    })

    $(document).on('click','#deleteforum', function(){
        var forum_id = $('#forum_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + forum_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                forum_id: forum_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#forumDeleteModel').modal('hide')
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
$(document).on('click','#filter_forum',function(){
    var startDate = $('.filter_form #daterange').data('daterangepicker').startDate;
    var endDate = $('.filter_form #daterange').data('daterangepicker').endDate;
    var status = $('.filter_form #status_forum').val();
    var createdBy = $('.filter_form #createdBy').val();
    fromDate = startDate.format('YYYY-MM-DD');
    toDate = endDate.format('YYYY-MM-DD');
    DatatableInitiate(status,fromDate,toDate,createdBy);
});

// song comments
$(document).on('click','.showCommentList',function(){
    var songId = $(this).data('id');
    // alert(songId);
    $('#clickSongId').val(songId);
    $('#postSongCommentList').submit();
    
});

function DatatableInitiate(status='',fromDate='',toDate='',createdBy='') {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by Topic, Created By..."
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
                    targets: [0,5],
                    className: "hide_column"
                },
                {
                    targets: [1],
                    className: "text-center opacity1",
                },
                {
                    targets: [1,4,5,6],
                    className: "text-center"
                },
                {
                    targets: [2,3],
                    className: "text-left"
                },
                {
                    targets: [2,3,4,5,6],
                    "orderable": true
                },
                {
                    targets: [1,7],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[4, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
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