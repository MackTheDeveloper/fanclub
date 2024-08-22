<div class="modal fade addToPlaylistModal" id="addToPlaylistModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add to Playlist</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img
                            src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                </button>
            </div>
            <form id="formAddToPlaylist" method="POST" action="{{ url('create-playlist-and-add-song') }}" class="modal-body">
                @csrf
                <div class="inputs-group">
                    <input id="playlist_name" name="playlist_name" type="text" />
                    <span>Enter Name of Playlist*</span>
                </div>
                <div class="m-footer">
                    <button class="fill-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>