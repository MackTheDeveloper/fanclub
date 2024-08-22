<form method="POST" action="{{ url('update-fan-playlist') }}" id="formEditFanPlaylist">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="fanPlaylistId" name="id" value="{{ $fanPlaylistData->id }}">
    <div class="">
        <div class="inputs-group">
            <input type="text" name="playlist_name" class="has-value" value="{{ $fanPlaylistData->playlist_name }}">
            <span>Enter Name of Playlist*</span>
        </div>
    </div>

    <div class="m-footer">
        <button type="submit" class="fill-btn">Submit</button>
    </div>
</form>


<script type="text/javascript">
    $("#formEditFanPlaylist").validate({
        ignore: [],
        rules: {
            playlist_name: 'required',
        },
        submitHandler: function(form) {
            form.submit();
           /*  $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#editFanPlaylistModal').modal('hide');
                    if (response.statusCode == '200') {
                        window.location.href = '{{url('my-playlist')}}' + '/' + response.component.data.slug;
                        setTimeout(function() {
                            $('.data-wrapper h3').text(response.component.data.playlist_name);
                        }, 500);
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                    } else {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            }); */
        }
    });
</script>
