// $('#Tdatatable').dataTable( {
//     language: {
//         searchPlaceholder: "Search By Song Name... "
//     }
// } );

/** music cateogrys listing */
$(document).ready(function(){
    var origin = window.location.href;
    // DatatableInitiate();
    /** delete music cateogry */
    $(document).on('click', 'tbody .fan_delete', function () {
        var fan_playlist_song_id = $(this).data('id');
        var message = "Are you sure ?";
        console.log(message);
        $('#fanPlaylistSongDeleteModel').on('show.bs.modal', function(e){
            $('#fan_playlist_song_id').val(fan_playlist_song_id);
            $('#message_delete').text(message);
        });
        $('#fanPlaylistSongDeleteModel').modal('show');
    })

    $(document).on('click','#deletefanPlaylistSong', function(){
        var fan_playlist_song_id = $('#fan_playlist_song_id').val();
        $.ajax({
            url: origin + '/../delete/' + fan_playlist_song_id,
            method: "POST",
            data: {
                "_token": $('#token').val(),
                fan_playlist_song_id: fan_playlist_song_id,
            },
            success: function(response)
            {
                if(response.status == 'true')
                {
                    $('#fanPlaylistSongDeleteModel').modal('hide')
                    // DatatableInitiate();
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.msg);
                }
                else
                {
                    $('#fanPlaylistSongDeleteModel').modal('hide')
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.error(response.msg);
                }
                setTimeout(function(){
                    toastr.clear();
                    window.location.reload();
                }, 3000);
            }
        });
    })
})
