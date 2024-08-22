/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    var artists = $('#artists').val();
    var songs = $('#songs').val();
    DatatableInitiate('','',artists,songs);

    /** delete music cateogry */
    $('#Tdatatable').on('click', 'tbody .comment_delete', function () {
        var comment_id = $(this).data('id');  
        var message = "Are you sure ?";   
        console.log(message);       
        $('#commentDeleteModel').on('show.bs.modal', function(e){
            $('#comment_id').val(comment_id);
            $('#message_delete').text(message);
        });
        $('#commentDeleteModel').modal('show');              
    })

    $(document).on('click','#deletemusicGenres', function(){
        var comment_id = $('#comment_id').val(); 
        $.ajax({
            url: origin + '/../delete/' + comment_id,
            method: "POST",    
            data: {
                "_token": $('#token').val(),
                comment_id: comment_id,
            },            
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#commentDeleteModel').modal('hide')
                    DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#commentDeleteModel').modal('hide')                  
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
$(document).on('click','#filter_comments',function(){
    var startDate = $('#daterange').data('daterangepicker').startDate;
    var endDate = $('#daterange').data('daterangepicker').endDate;
    startDate = startDate.format('YYYY-MM-DD');
    endDate = endDate.format('YYYY-MM-DD');
    var artists = $('#artists').val();
    var songs = $('#songs').val();
    DatatableInitiate(startDate,endDate,artists,songs)
});

$(document).on('change','#artists',function(){
    var value = $(this).val();
    // alert(value);
    $.ajax({
        url: "get-songs", // json datasource
        data: {artist:value},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type:'post',
        success: function (response) {
            $('#songs').html(response);
        }
    })
});

$(document).on('click','.comment_view',function(){
    var commentId = $(this).data('id');
    // alert(value);
    $.ajax({
        url: "view/"+commentId, // json datasource
        // headers: {
        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // },
        // type:'post',
        success: function (response) {
            $('#showCommentText').html(response);
            $('#commentShowModel').modal('show');
        }
    })
});

function DatatableInitiate(startDate='',endDate='',artists='',songs='') {
    $('#Tdatatable').DataTable(
        {
            language: {
                searchPlaceholder: "Search by User Name..."
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
                    targets: [0],
                    className: "hide_column"
                },
                {
                    targets: [2,4],
                    "orderable": true
                },
                {
                    targets: [1,3,5],
                    "orderable": false
                },
                {
                    targets: [2,3,4],
                    className: "text-left"
                },
                {
                    targets: [1,5],
                    className: "text-center"
                },
                {
                    targets: [5],
                    className: "text-center", orderable: false, searchable: false
                }
            ],
            "order": [[4, "desc"]],
            "scrollX": true,
            "ajax": {
                url: "list", // json datasource
                data: {
                    startDate:startDate,
                    endDate:endDate,
                    artists:artists,
                    songs:songs
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