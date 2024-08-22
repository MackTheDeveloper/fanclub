<div class="modal fade edit-photo-popup" id="modal-crop-image" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Crop Image</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img
                            src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="crop-img-wrapper">
                        <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                        <div class="preview d-none"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="fill-btn" id="crop">Crop</button>
                <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div>
</div>
