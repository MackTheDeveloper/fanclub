<form method="POST" action="{{ url('add-review') }}" id="formAddReview">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <input type="hidden" class="song_id" name="song_id" value="{{ $songId }}">
    <input type="hidden" class="artist_id" name="artist_id" value="{{ $artistId }}">
    <h6>Rate This Video</h6>
    <div class="add-ratting-star" data-aos="fade-up">
        <ul id='stars'>
            <li class='star red-star' title='Poor' data-value='1'></li>
            <li class='star green-star' title='Fair' data-value='2'></li>
            <li class='star green-star' title='Good' data-value='3'></li>
            <li class='star yellow-star' title='Excellent' data-value='4'>
            </li>
            <li class='star yellow-star' title='WOW!!!' data-value='5'></li>
        </ul>
        <input id="star-value" class="hidden-input" name="rating" />
    </div>
    <div class="inputs-group">
        <textarea name="comment"></textarea>
        <span>Add Review*</span>
    </div>

    <div class="m-footer">
        <button class="fill-btn">Submit</button>
    </div>
</form>


<script type="text/javascript">
    $("#formAddReview").validate({
        ignore: [],
        rules: {
            comment: 'required',
        },
        submitHandler: function(form) {
            //form.submit();
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    $('#addReviewModal').modal('hide');
                    $.ajax({
                        url: '{{url("get-music-player-song-data")}}',
                        type: 'post',
                        data:'songId='+'{{$songId}}'+'&_token={{ csrf_token() }}',
                        success: function(response) {
                            $('.music-player-song').html(response)
                        }
                    });

                    $.ajax({
                        url: '{{url("get-music-player-review-data")}}',
                        type: 'post',
                        data:'songId='+'{{$songId}}'+'&_token={{ csrf_token() }}',
                        success: function(response) {
                            $('.music-player-reviews').html(response)
                        }
                    });

                    if (response.statusCode == '200') {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                    } else {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            });
        }
    });
</script>
