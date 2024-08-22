// OTP JS
toastr.options.timeOut = 10000;
$(document).on('click','#imageUpload1',function(){
    $('#imageUpload1').val('');
});
let digitValidate = function (ele) {
    ele.value = ele.value.replace(/[^0-9]/g, '');
}
$('.otp-input').find('input').each(function () {
    $(this).attr('maxlength', 1);
    $(this).on('keyup', function (e) {
        var parent = $($(this).parent());
        if (e.keyCode === 8 || e.keyCode === 37) {
            var prev = parent.find('input#' + $(this).data('previous'));
            if (prev.length) {
                $(prev).select();
            }
        } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
            var next = parent.find('input#' + $(this).data('next'));
            if (next.length) {
                $(next).select();
            } else {
                $('#otp-input').val($('#digit-1').val() + $('#digit-2').val() + $('#digit-3').val() + $('#digit-4').val())
                if (parent.closest('form').attr('id') == 'otpVerificationForm')
                    $('#otpVerificationForm').submit();
                else if (parent.closest('form').attr('id') == 'loginWithOtpVerificationFormFromPopup')
                    $('#loginWithOtpVerificationFormFromPopup').submit();
                /* if (parent.data('autosubmit')) {
                    parent.submit();
                } */
            }
        }
    });
});

function closeSortPopup(){
    $(".sortMenu").removeClass("active");
    $("body").removeClass("scroll-stop")
}


function setStarbyVal(thisStar){
  // startVal
    var startVal = thisStar.val();
    thisStar.closest('.starsDiv').find('.star').each(function(){
        var thisVal = $(this).data('value');
        if (startVal>=thisVal) {
            $(this).addClass('selected');
        }else{
            $(this).removeClass('selected');
        }
    })
    // starsDiv
}

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

$(document).on('click', '#btnAddToPlayList', function(){
    var refresh_page = 0;
    if ($(this).hasClass("refresh-page")) {
        refresh_page = 1;
    }
    $("#addToPlaylistModal")
        .modal("show")
        .find("#modalContentAddToPlayList")
        .load($(this).attr("value"), function () {
            $("#addToPlaylistModal")
                .find("#formAddToPlaylist .refresh_page")
                .val(refresh_page);
        });
    
});
$(document).on('click', '#btnRemoveFromPlayList', function(){
    $('#removeFromPlaylistModal').modal('show').find('#modalContentRemoveFromPlayList').load($(this).attr('value'));
});
$(document).on('click', '#btnEditFanPlayList', function(){
    $('#editFanPlaylistModal').modal('show').find('#modalContentEditFanPlaylist').load($(this).attr('value'));
});
$(document).on('click', '#btnAddReview', function(){
    $('#addReviewModal').modal('show').find('#modalContentAddReview').load($(this).attr('value'));
});

/* $( ".blobVideoUrlDownload" ).each(function( index, val ) {
    var thiz = $(this);
    var request = new XMLHttpRequest();
        request.open("GET", val, true);
        request.responseType = "blob";

        request.onload = function () {
            if (this.status === 200) {
                var videoBlob = this.response;
                var videoUrl = URL.createObjectURL(videoBlob);
                $(thiz).attr("href", videoUrl);
            }
        }
        request.send();
  }); */


// Erase cookie
//document.cookie = 'cookiePolicy' +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

var x = getCookie('cookiePolicy');
if (!x) {
    setCookie('cookiePolicy', 'fanclub', 365);
}else{
    document.getElementById("cookie-alert").classList.add("d-none");
}

$(document).on('click', '.close-cookie-alert', function(){
    $('.cookie-alert').addClass('hide');
});