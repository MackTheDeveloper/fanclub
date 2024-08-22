<div class="modal fade addNewsPopup" id="addBannerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Banner</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img
                            src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                </button>
            </div>
            <form id="news-add" class="modal-body" method="POST" enctype="multipart/form-data" action="{{ route('artistBannerCreate') }}">
                @csrf
                <input type="hidden" class="hiddenPreviewImg" name="hiddenPreviewImg" value="" />
                <div class="file-upload">
                    <div class="image-box upload-s_banner">
                        <img src="{{ asset('public/assets/frontend/img/cne-img_654_368.svg') }}" alt="" class="height-img">
                    </div>
                    <small class="form-text text-muted">Image size should be {{config('app.artistBannerDimentions.width')}} X {{config('app.artistBannerDimentions.height')}} px</small>
                </div>
                <input type="file" class="d-none imageFile" id="imageUpload1" name="banner" />
                <div class="m-footer">
                    <button type="submit" class="fill-btn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>