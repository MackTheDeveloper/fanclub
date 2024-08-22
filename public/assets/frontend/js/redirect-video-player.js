var baseUrl = document.currentScript.getAttribute('data-base-url');
$(document).on('click', '.playSingleSongInPlayer', function () {
    if($(this).hasClass('loginBeforeGo')){
        return false;
    }
    var songId = $(this).data('song-id');
    url = baseUrl + '/my-music-player';
    var newForm = $('#singleSongInPlayerForm');
    newForm.attr('action', url);
    $('#singleSongInPlayerForm #singleSongInPlayer-songId').val(songId);
    newForm.submit();
}) 

$(document).on('click', '.download-all', function () {
    if($(this).hasClass('loginBeforeGo')){
        return false;
    }
    var page = $(this).data('page');
    var slug = $(this).data('slug');
    url = baseUrl + '/download-all';
    var newForm = $('#downloadAllForm');
    newForm.attr('action', url);
    $('#downloadAllForm #slug').val(slug);
    $('#downloadAllForm #page').val(page);
    newForm.submit();
}) 