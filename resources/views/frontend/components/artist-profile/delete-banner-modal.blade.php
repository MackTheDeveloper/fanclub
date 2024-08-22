<div class="modal fade deletePopup" id="deleteBannerModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ asset('public/assets/frontend/img/modal-close.svg') }}" class="close"
                        data-bs-dismiss="modal" aria-label="Close" />

                    <div class="delete-photo">
                        <img src="{{ asset('public/assets/frontend/img/delete-popup-img.svg') }}" alt=""
                            class="web-view" />
                        <img src="{{ asset('public/assets/frontend/img/delete-popup-img-mobile.svg') }}" alt=""
                            class="mobile-view" />
                    </div>
                    <div class="delete-content">
                        <h5 class="modal-title">Hey, Wait!!</h5>
                        <p class="blur-color">Are you sure you want to delete the record? <br> This process can't be
                            undone</p>
                        <input type="hidden" name="bannerId" id="bannerId">

                        <div class="delete-footer">
                            <button type="button" class="border-btn" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="button" class="fill-btn deleteBannerConfirm">Delete</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>