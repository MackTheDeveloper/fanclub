<div class="modal fade deletePopup" id="uploadSuccessModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ asset('public/assets/frontend/img/modal-close.svg') }}" class="close"
                    data-bs-dismiss="modal" aria-label="Close" />

                <div class="delete-photo">
                    <img src="{{ asset('public/assets/frontend/img/success-upload-img.svg') }}" alt=""
                        class="web-view" />
                    <img src="{{ asset('public/assets/frontend/img/success-upload-img-mobile.svg') }}" alt=""
                        class="mobile-view" />
                </div>
                <div class="delete-content">
                    <h5 class="modal-title">Success!!</h5>
                    <p class="blur-color success-msg"></p>
                    <div class="delete-footer">
                        <button type="button" class="border-btn" data-bs-dismiss="modal"
                            aria-label="Close">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade deletePopup" id="uploadFailedModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ asset('public/assets/frontend/img/modal-close.svg') }}" class="close"
                    data-bs-dismiss="modal" aria-label="Close" />

                <div class="delete-photo">
                    <img src="{{ asset('public/assets/frontend/img/failed-upload-img.svg') }}" alt=""
                        class="web-view" />
                    <img src="{{ asset('public/assets/frontend/img/failed-upload-img-mobile.svg') }}" alt=""
                        class="mobile-view" />
                </div>
                <div class="delete-content">
                    <h5 class="modal-title">Failed!</h5>
                    <p class="blur-color success-msg"></p>
                    <div class="delete-footer">
                        <button type="button" class="border-btn" data-bs-dismiss="modal"
                            aria-label="Close">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
