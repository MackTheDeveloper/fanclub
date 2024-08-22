<img src="{{ asset('public/assets/frontend/img/modal-close.svg') }}" class="close" data-bs-dismiss="modal"
    aria-label="Close" />

<div class="delete-photo">
    <img src="{{ asset('public/assets/frontend/img/delete-popup-img.svg') }}" alt="" class="web-view" />
    <img src="{{ asset('public/assets/frontend/img/delete-popup-img-mobile.svg') }}" alt="" class="mobile-view" />
</div>
<div class="delete-content">
    <h5 class="modal-title">Hey, Wait!!</h5>
    <p class="blur-color">Are you sure you want to delete the record? <br> This process can't be undone</p>
    <input type="hidden" name="id" id="id" value="{{ $id }}">
    <input type="hidden" name="slug" id="slug" value="{{ $slug }}">

    <div class="delete-footer">
        <button type="button" class="border-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        <button type="button" class="fill-btn deleteFromPaylistConfirm">Delete</button>
    </div>
</div>

<script type="text/javascript">
    $('.deleteFromPaylistConfirm').click(function() {
        var id= $('#id').val();
        var slug= $('#slug').val();
        var token = "{{ csrf_token() }}";
        if (id) {
            $.ajax({
                url: "{{ url('remove-from-playlist') }}",
                method: 'post',
                data: {
                    id: id,
                    slug: slug,
                    _token: token
                },
                success: function(response) {
                    $('#removeFromPlaylistModal').modal('hide');
                    if (response.statusCode == '200') {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    }
                }
            });
        } else {

        }
    })
</script>
