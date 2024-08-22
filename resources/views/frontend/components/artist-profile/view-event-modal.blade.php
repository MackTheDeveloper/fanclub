<div class="modal fade newsPopup" id="upcomingEventModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <div class="time-location-popup">
                        <a class="location" href="javascript:void(0)">
                            <img src="{{ asset('public/assets/frontend/img/location-black.svg') }}" alt="" />
                            <span>Unknown</span>
                        </a>
                        <div class="time">
                            <img src="{{ asset('public/assets/frontend/img/time-black.svg') }}" alt="" />
                            <span>Undefined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>