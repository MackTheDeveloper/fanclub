<div class="modal fade aboutchangepopup" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">About Me</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img
                            src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="bio">About Me</label>
                        <textarea placeholder="Add description here..."
                            id="bio">{{ $content['artistDetail']->artistDetailData->aboutFullDesc }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-label="Close" class="fill-btn submitBio">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>
