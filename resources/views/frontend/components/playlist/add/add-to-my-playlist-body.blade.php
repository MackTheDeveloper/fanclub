<form method="POST" action="{{ url('create-playlist-and-add-song') }}" id="formAddToPlaylist">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="song_id" name="song_id" value="{{ $songId }}">
    <input type="hidden" class="song_id" name="songs[]" value="{{ $songId }}">
    <input type="hidden" class="refresh_page" value="0">
    <div class="existing-playlist">
        <h6>Existing Playlists</h6>

        <div class="exist-play-scroll">
            @foreach ($playListData as $item)
                <a class="existing-box" data-playlistid={{ $item->playlistId }}>
                    <img src="{{ $item->playListIcon }}" alt="" />
                    <div class="existing-content">
                        <span>{{ $item->playlistName }}</span>
                        <p class="caption blur-color">Updated on {{substr($item->createdAt,0,14)}}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="create-new-playlist">
        <h6>Create New Playlist</h6>
        <div class="inputs-group">
            <input type="text" name="playlist_name">
            <span>Enter Name of Playlist*</span>
        </div>
    </div>

    <div class="m-footer">
        <button type="submit" class="fill-btn">Create Playlist</button>
    </div>
</form>


<script type="text/javascript">
    $("#formAddToPlaylist").validate({
        ignore: [],
        rules: {
            playlist_name: 'required',
        },
        submitHandler: function(form) {
            var refresh_page = $('.refresh_page').val();
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#addToPlaylistModal').modal('hide');
                    if (response.statusCode == '200') {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        if (refresh_page==1) {
                            setTimeout(function(){
                                toastr.clear();
                                window.location.reload();
                            }, 1000);
                        }
                    } else {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            });
        }
    });
    $('.existing-box').click(function() {
        var songId = $('.song_id').val();
        var playListId = $(this).data('playlistid');
        var token = $('#token').val();
        $.ajax({
            url: '{{ url('add-to-playlist') }}',
            type: 'post',
            data: {
                'song_id': songId,
                'playlist_id': playListId,
                '_token': token
            },
            success: function(response) {
                $('#addToPlaylistModal').modal('hide');
                if (response.statusCode == '200') {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.success(response.message);
                } else {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.error(response.component.error);
                }
            }
        });
    })
</script>
