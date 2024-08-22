var baseUrl = document.currentScript.getAttribute("data-base-url");
//ELEMENT SELECTORS
var player = document.querySelector(".video-thumbnail");
var currentTime = 0;
var currentVolume = 0;
var totalTimePlayed = 0;
var lastUpdatedTime = 0;
var viewIncreased = 0;
var streamIncreased = 0;
var recentAdded = 0;
var fullscreen = false;
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    var currentQty = $('.quality-content input[name="quality"]:checked');
    if (currentQty) {
        $(".quality-link .video-menu-content span:first").text(
            currentQty.data("show")
        );
    }
    $(".play-speed-link").click(function () {
        $(".video-main-menu").addClass("hide");
        $(".play-speed-content").addClass("show");
    });
    $(".quality-link").click(function () {
        $(".video-main-menu").addClass("hide");
        $(".quality-content").addClass("show");
    });
    $(".back-to-menu").click(function () {
        $(".video-main-menu").removeClass("hide");
        $(".play-speed-content").removeClass("show");
        $(".quality-content").removeClass("show");
    });

    $(".toggle-current-ellips").click(function () {
        $(this).toggleClass("ellips-add");
    });
    addToRecent();
});

$("#sortable").sortable({
    handle: ".drag-handle",
    containment: ".song-list",
});

function getCurrentTag() {
    var playing = $("#video").hasClass("hidden") ? "audio" : "video";
    // return $('#'+playing);
    return document.getElementById(playing);
}

function getCurrentTagName() {
    var playing = $("#video").hasClass("hidden") ? "audio" : "video";
    // return $('#'+playing);
    return playing;
}

var slide = $(".video-progress").slider({
    min: 0,
    max: 100,
    value: 0,
    range: "min",
    slide: function (event, ui) {
        getCurrentTag().currentTime = ui.value;
    },
});

var volumeSlide = $(".volume-progress").slider({
    min: 0,
    max: 100,
    value: 100,
    step: 1,
    range: "min",
    orientation: "vertical",
    slide: function (event, ui) {
        setVolume(ui.value / 100);
        if (ui.value == 0) {
            $(".sound-btn-click").addClass("mute-btn");
            $(".sound-btn-click").removeClass("half-btn");
        } else if (ui.value < 50) {
            $(".sound-btn-click").addClass("half-btn");
            $(".sound-btn-click").removeClass("mute-btn");
        } else {
            $(".sound-btn-click").removeClass("mute-btn");
            $(".sound-btn-click").removeClass("half-btn");
        }
    },
});

function setVolume(myVolume) {
    getCurrentTag().volume = myVolume;
}

$(document).on("click", ".sound-btn-click", function () {
    $(this).toggleClass("mute-btn");
    if ($(this).hasClass("mute-btn")) {
        currentVolume = getCurrentTag().volume;
        getCurrentTag().volume = 0;
        volumeSlide.slider("option", "value", 0);
        $(".sound-btn-click").removeClass("half-btn");
    } else {
        getCurrentTag().volume = currentVolume;
        volumeSlide.slider("option", "value", currentVolume * 100);
        if (currentVolume < 0.5) {
            $(".sound-btn-click").addClass("half-btn");
        }
    }
});

pipButton.addEventListener("click", function () {
    if (!document.pictureInPictureElement) {
        getCurrentTag()
            .requestPictureInPicture()
            .catch((error) => {
                // Video failed to enter Picture-in-Picture mode.
            });
    } else {
        document.exitPictureInPicture().catch((error) => {
            // Video failed to leave Picture-in-Picture mode.
        });
    }
});

getCurrentTag().addEventListener(
    "loadeddata",
    function () {
        playBtn.dispatchEvent(new Event("click"));
        var duration = getCurrentTag().duration;
        slide.slider("option", "max", duration);
    },
    false
);

getCurrentTag().addEventListener("ended", function () {
    playBtn.classList.toggle("paused");
    togglePlayBtn();
});

var playBtn = document.querySelector(".play-btn");
var fullscreenBtn = document.querySelector(".fullscreen");

//PLAYER FUNCTIONS
function togglePlay() {
    if (getCurrentTag().paused) {
        getCurrentTag().play();
    } else {
        getCurrentTag().pause();
    }
    playBtn.classList.toggle("paused");
}
function togglePlayBtn() {
    if (getCurrentTag().paused) {
        playBtn.classList.remove("playing");
    } else {
        playBtn.classList.add("playing");
    }
}

function endVideoAndNext() {
    var duration = getCurrentTag().duration;
    if (duration < 30) {
        increaseView();
    }
    resetAllValues();
    playBtn.classList.remove("playing");
    // var nextPlay = $(".playQueueSong.activePlaying").next("li");
    var nextPlay = $(".activePlaying").closest("li").next("li").find('.playQueueSong');
    // console.log('nextPlay::', nextPlay);
    nextPlay.trigger("click");
    $('.play-speed-content input[name="speed"][value="1"]').trigger("click");
}

function updateProgress1(e) {
    slide.slider("value", getCurrentTag().currentTime);
}

function updateProgress2(e) {
    slide.slider("value", getCurrentTag().currentTime);
}

function launchIntoFullscreen(element) {
    if (element.requestFullscreen) {
        element.requestFullscreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullscreen) {
        element.webkitRequestFullscreen();
    } else if (element.msRequestFullscreen) {
        element.msRequestFullscreen();
    }
}
function exitFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
    }
}

function toggleFullscreen() {
    fullscreen ? exitFullscreen() : launchIntoFullscreen(player);
    fullscreen = !fullscreen;
    fullscreenBtn.classList.toggle("fullscreen-apply");
    makeLandscape();
}

function makeLandscape() {
    if (screen.orientation && screen.orientation.lock) {
        screen.orientation.lock("landscape");
    }
}

function handleKeypress(e) {
    switch (e.key) {
        case " ":
            togglePlay();
        case "ArrowRight":
            getCurrentTag().currentTime += 10;
        case "ArrowLeft":
            getCurrentTag().currentTime -= 10;
        default:
            return;
    }
}

function togglePlayParent(e) {
    if (e.target.tagName == "VIDEO") {
        togglePlay();
    }
}

function totalTimePlayedCalc() {
    var newTime = getCurrentTag().currentTime;
    var duration = getCurrentTag().duration;
    var timeDiff = newTime - lastUpdatedTime;
    if (timeDiff > 0 && timeDiff < 2) {
        totalTimePlayed += timeDiff;
    }
    lastUpdatedTime = newTime;
    if (totalTimePlayed >= 30) {
        increaseView();
    }
    if (totalTimePlayed >= duration / 2) {
        // console.log('duration',duration);
        increaseStream();
    }
    // console.log('totalTimePlayed',totalTimePlayed);
}

function resetAllValues() {
    currentTime = 0;
    // currentVolume = 0;
    totalTimePlayed = 0;
    lastUpdatedTime = 0;
    viewIncreased = 0;
    streamIncreased = 0;
    recentAdded = 0;
    // fullscreen = false;
}

$(document).on(
    "change",
    '.play-speed-content input[name="speed"]',
    function () {
        getCurrentTag().playbackRate = $(this).val();
        $(".play-speed-link .video-menu-content span:first").text(
            $(this).data("show")
        );
    }
);

$(document).on("change", '.quality-content input[name="quality"]', function () {
    var currentTag = getCurrentTagName();
    if (currentTag == "video") {
        // getCurrentTag().pause();
        $(".quality-link .video-menu-content span:first").text(
            $(this).data("show")
        );
        var slug = $('input[name="current-song-slug"]').val();
        var url = baseUrl + "/song-access/" + slug + "/" + $(this).val();

        //var urlForDownload = baseUrl + "/song-download/" + slug + "/" + $(this).val();
        //$('.songVideoDownload').val(urlForDownload);

        // var currentTimenew = getCurrentTag().currentTime;
        // var currentPlayback = getCurrentTag().playbackRate;
        // var newurl = "https://fanclub-media.s3.amazonaws.com/images/302801471_1638532277.mp4";

        var request = new XMLHttpRequest();
        request.open("GET", url, true);
        /* set the response to blob type */
        request.responseType = "blob";

        request.onload = function () {
            if (this.status === 200) {
                getCurrentTag().pause();
                var currentTimenew = getCurrentTag().currentTime;
                var currentPlayback = getCurrentTag().playbackRate;
                var videoBlob = this.response;
                /* create the video URL from the blob */
                var videoUrl = URL.createObjectURL(videoBlob);
                /* set the video URL as source on the video element */
                //video.src = videoUrl;

                $("video source").attr("src", videoUrl);
                $("video")[0].load();
                getCurrentTag().currentTime = currentTimenew;
                getCurrentTag().playbackRate = currentPlayback;
            }
        };
        request.send();
        // getCurrentTag().currentTime = currentTime;
        // getCurrentTag().play();
    }
});
// getCurrentTag().addEventListener(
//     "loadedmetadata",
//     function () {
//         this.currentTime = currentTime;
//     },
//     false
// );

getCurrentTag().onwaiting = function () {
    $(".video-loader-wrapper").removeClass("d-none");
};
getCurrentTag().onplaying = function () {
    $(".video-loader-wrapper").addClass("d-none");
};

//EVENT LISTENERS
playBtn.addEventListener("click", togglePlay);
document.getElementById("video").addEventListener("click", togglePlay);
document.getElementById("audio").addEventListener("click", togglePlay);
document.getElementById("video").addEventListener("play", togglePlayBtn);
document.getElementById("audio").addEventListener("play", togglePlayBtn);
document.getElementById("video").addEventListener("pause", togglePlayBtn);
document.getElementById("audio").addEventListener("pause", togglePlayBtn);
document.getElementById("video").addEventListener("ended", endVideoAndNext);
document.getElementById("audio").addEventListener("ended", endVideoAndNext);
document
    .getElementById("video")
    .addEventListener("timeupdate", updateProgress1);
document
    .getElementById("audio")
    .addEventListener("timeupdate", updateProgress2);
document
    .getElementById("video")
    .addEventListener("timeupdate", totalTimePlayedCalc);
document
    .getElementById("audio")
    .addEventListener("timeupdate", totalTimePlayedCalc);
document.getElementById("video").addEventListener("canplay", updateProgress1);
document.getElementById("audio").addEventListener("canplay", updateProgress2);
// document.getElementById("video").addEventListener("loadstart", showLoader);
// document.getElementById("audio").addEventListener("loadstart", showLoader);

fullscreenBtn.addEventListener("click", toggleFullscreen);

window.addEventListener("keydown", handleKeypress);
var except = document.getElementById("modalContentAddReview");
except.addEventListener("keydown", function (ev) {
    ev.stopPropagation();
});

$(document).on("change", "#switch", function () {
    var playing = $("#video").hasClass("hidden") ? "audio" : "video";
    // var vid = document.getElementById("audiovideoSwitch");
    var video = document.getElementById("video");
    var audio = document.getElementById("audio");
    // var currentTime = vid.currentTime;
    switch (playing) {
        case "video":
            if (video.paused) {
                video.setAttribute("class", "hidden");
                audio.currentTime = video.currentTime;
                audio.playbackRate = video.playbackRate;
                audio.volume = video.volume;
                audio.setAttribute("class", "");
            } else {
                video.pause();
                video.setAttribute("class", "hidden");
                audio.currentTime = video.currentTime;
                audio.playbackRate = video.playbackRate;
                audio.volume = video.volume;
                audio.setAttribute("class", "");
                audio.play();
                // playing = "audio";
            }
            break;
        case "audio":
            if (audio.paused) {
                audio.setAttribute("class", "hidden");
                video.currentTime = audio.currentTime;
                video.playbackRate = audio.playbackRate;
                video.volume = audio.volume;
                video.setAttribute("class", "");
            } else {
                audio.pause();
                audio.setAttribute("class", "hidden");
                // audio.removeChild(source);
                // video.appendChild(source);
                video.currentTime = audio.currentTime;
                video.playbackRate = audio.playbackRate;
                video.volume = audio.volume;
                video.setAttribute("class", "");
                video.play();
                // playing = "video";
            }
            break;
    }

    if ($(this).prop("checked") == true) {
        $("#pipButton").addClass("disabled-btn");
        $(".fullscreen").addClass("disabled-btn");
    } else {
        $("#pipButton").removeClass("disabled-btn");
        $(".fullscreen").removeClass("disabled-btn");
    }
});

$(document).on("click", ".default-star-box", function () {
    $(".only-list-wrapper").addClass("leftList");
    $(".only-review-wrapper").addClass("upReview");
});
$(document).on("click", ".close-review-btn", function () {
    $(".only-list-wrapper").removeClass("leftList");
    $(".only-review-wrapper").removeClass("upReview");
});

/* $(document).on('click', '.download-video', function() {
    if ($('#switch').prop('checked')) {
        window.location.href = $('.songAudioDownload').val();
    } else {
        window.location.href = $('.songVideoDownload').val();
    }
}); */

$(document).on("click", ".playQueueSong", function () {
    currentTime = 0;
    var songId = $(this).data("song-id");
    $(this)
        .closest("li")
        .addClass("activePlaying")
        .siblings()
        .removeClass("activePlaying");
    var getMusicPlayerDataUrl = baseUrl + "/get-music-player-data";
    var getMusicPlayerSongDataUrl = baseUrl + "/get-music-player-song-data";
    var getMusicPlayerReviewDataUrl = baseUrl + "/get-music-player-review-data";
    $.ajax({
        url: getMusicPlayerDataUrl,
        type: "post",
        data: "songId=" + songId,
        success: function (response) {
            $(".video-loader-wrapper").removeClass("d-none");
            // console.log(response);
            var videoSrc = response.playerSong.playerSongData.data.songVideo;
            $("video source").attr("src", videoSrc);
            $("video")[0].load();
            $("audio").attr(
                "src",
                response.playerSong.playerSongData.data.songAudio
            );
            // change current slug
            $('input[name="current-song-slug"]').val(
                response.playerSong.playerSongData.data.songSlug
            );

            /* $(".songAudioDownload").val(
                response.playerSong.playerSongData.data.songAudioDownload
            );
            $(".songVideoDownload").val(
                response.playerSong.playerSongData.data.songVideoDownload
            ); */

            $(".video-thumbnail .video-likes input").prop(
                "checked",
                response.playerSong.playerSongData.data.songLike == 1
                    ? true
                    : false
            );
            $(".video-thumbnail .video-likes input").attr(
                "data-id",
                response.playerSong.playerSongData.data.songId
            );

            $(".quality-content .video-content-scroll").html("");
            $(response.player.playerData.quality).each(function (key, val) {
                var checked = key == 0 ? "checked" : "";
                var txt =
                    "<label class='rightCk back-to-menu'><span>" +
                    val.value +
                    "</span><input type='radio' value=" +
                    val.key +
                    " data-show=" +
                    val.value +
                    " name='quality' " +
                    checked +
                    "><span class='right-checkmark'></span></label>";
                $(".quality-content .video-content-scroll").append(txt);
                if (key == 0) {
                    $(".quality-link .video-menu-content span:first").text(
                        val.value
                    );
                }
            });

            resetAllValues();
            addToRecent();
        },
    });

    $.ajax({
        url: getMusicPlayerSongDataUrl,
        type: "post",
        data: "songId=" + songId,
        success: function (response) {
            $(".music-player-song").html(response);
        },
    });

    $.ajax({
        url: getMusicPlayerReviewDataUrl,
        type: "post",
        data: "songId=" + songId,
        success: function (response) {
            $(".music-player-reviews").html(response);
        },
    });
});

$(document).on("mouseover mousemove", ".video-thumbnail", function () {
    $(".video-controller").addClass("show");
    $(".video-header").addClass("show");
    setTimeout(function () {
        $(".video-controller").removeClass("show");
        $(".video-header").removeClass("show");
    }, 3000);
});

$(document).on("click", ".dropdown", function () {
    $(".video-controller").addClass("click-show");
    $(".video-header").addClass("click-show");
});

$("body").click(function (evt) {
    if (evt.target.class == "settingDropdown") {
        $(".video-controller").addClass("click-show");
        $(".video-header").addClass("click-show");
    } else if ($(evt.target).closest(".settingDropdown").length) {
        $(".video-controller").addClass("click-show");
        $(".video-header").addClass("click-show");
    } else {
        $(".video-controller").removeClass("click-show");
        $(".video-header").removeClass("click-show");
    }
});

function increaseView() {
    if (viewIncreased == 0) {
        viewIncreased = 1;
        var slug = $('input[name="current-song-slug"]').val();
        var increaseViewUrl = baseUrl + "/song-increase-view";
        $.ajax({
            url: increaseViewUrl,
            type: "post",
            data: "slug=" + slug,
            success: function (response) {},
        });
    }
}
function increaseStream() {
    if (streamIncreased == 0) {
        streamIncreased = 1;
        var slug = $('input[name="current-song-slug"]').val();
        var increaseViewUrl = baseUrl + "/song-increase-stream";
        $.ajax({
            url: increaseViewUrl,
            type: "post",
            data: "slug=" + slug,
            success: function (response) {},
        });
    }
}
function addToRecent() {
    if (recentAdded == 0) {
        recentAdded = 1;
        var slug = $('input[name="current-song-slug"]').val();
        var increaseViewUrl = baseUrl + "/song-add-recent";
        $.ajax({
            url: increaseViewUrl,
            type: "post",
            data: "slug=" + slug,
            success: function (response) {},
        });
    }
}
