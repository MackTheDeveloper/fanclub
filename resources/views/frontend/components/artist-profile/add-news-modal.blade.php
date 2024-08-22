<div class="modal fade addNewsPopup" id="addNewsModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add News</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <form id="news-add" class="modal-body" method="POST" action="{{ route('artistNewsCreate') }}">
                    @csrf
                    <div class="inputs-group">
                        <input type="text" name="name" />
                        <span>News Title*</span>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Description*</label>
                        <textarea placeholder="Add description here..." name="description" id="exampleFormControlTextarea1"></textarea>
                    </div>
                    <div class="m-footer">
                        <button type="submit" class="fill-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>