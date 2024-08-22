var slide = $(".video-progress").slider({
  min: 0,
  max: 100,
  value: 0,
  range: "min",
  slide: function(event, ui) {
    var video2 = document.querySelector('#video');
    video2.currentTime = ui.value;
  }
});


var volumeSlide = $(".volume-progress").slider({
  min: 0,
  max: 100,
  value: 100,
  step: 1,
  range: "min",
  orientation: "vertical",
  slide: function(event, ui) {
    setVolume(ui.value / 100);
    if(ui.value == 0){
      $(".sound-btn-click").addClass("mute-btn")
      $(".sound-btn-click").removeClass("half-btn")
    }
    else if(ui.value < 50){
      $(".sound-btn-click").addClass("half-btn")
      $(".sound-btn-click").removeClass("mute-btn")
    }
    else{
      $(".sound-btn-click").removeClass("mute-btn")
      $(".sound-btn-click").removeClass("half-btn")
    }
  }
});
var currentVolume = 0;
function setVolume(myVolume) {
  var myMedia = document.querySelector('#video');
  myMedia.volume = myVolume;
}

$(document).on('click','.sound-btn-click',function(){
  $(this).toggleClass('mute-btn');
  var myMedia = document.querySelector('#video');
  if($(this).hasClass('mute-btn')){
    currentVolume = myMedia.volume;
    myMedia.volume = 0;
    volumeSlide.slider("option", "value", 0);
    $(".sound-btn-click").removeClass("half-btn")
  }else{
    myMedia.volume = currentVolume;
    volumeSlide.slider("option", "value", currentVolume*100);
    if(currentVolume<0.5){
      $(".sound-btn-click").addClass("half-btn");
    }
  }
})

var video = document.getElementById("#video")

pipButton.addEventListener('click', function() {
  if (!document.pictureInPictureElement) {
    video.requestPictureInPicture()
    .catch(error => {
      // Video failed to enter Picture-in-Picture mode.
    });
  } else {
    document.exitPictureInPicture()
    .catch(error => {
      // Video failed to leave Picture-in-Picture mode.
    });
  }
});

//ELEMENT SELECTORS
var player = document.querySelector('.video-thumbnail');
var video = document.querySelector('#video');


video.addEventListener('loadeddata', function() {
  var duration = video.duration;
  console.log('duration',duration)
  slide.slider("option", "max", duration);
}, false);

video.addEventListener('ended', function() {
  playBtn.classList.toggle('paused');
});


// slide.slider("option", "max", duration);
// $slide.slider("value", $slide.slider("value")); 
var playBtn = document.querySelector('.play-btn');
// var volumeBtn = document.querySelector('.volume-btn');
// var volumeSlider = document.querySelector('.volume-slider');
// var volumeFill = document.querySelector('.volume-filled');
// var progressSlider = document.querySelector('.progress');
// var progressFill = document.querySelector('.progress-filled');
// var textCurrent = document.querySelector('.time-current');
// var textTotal = document.querySelector('.time-total');
// var speedBtns = document.querySelectorAll('.speed-item');
var fullscreenBtn =document.querySelector('.fullscreen'); 

//GLOBAL VARS
// let lastVolume = 1;
// let isMouseDown = false;

//PLAYER FUNCTIONS
function togglePlay() {
	if (video.paused) {
		video.play();
	} else {
		video.pause();	
	}
	playBtn.classList.toggle('paused');
}
// function togglePlayBtn() {
// 	playBtn.classList.toggle('playing');
// }

// function toggleMute() {
// 	if(video.volume) {
// 		lastVolume = video.volume;
// 		video.volume = 0;
// 		volumeBtn.classList.add('muted');
// 		volumeFill.style.width = 0;
// 	} else {
// 		video.volume = lastVolume;
// 		volumeBtn.classList.remove('muted');
// 		volumeFill.style.width = `${lastVolume*100}%`;
// 	}
// }

// function changeVolume(e) {
// 		volumeBtn.classList.remove('muted');
// 		let volume = e.offsetX/volumeSlider.offsetWidth;
// 		volume<0.1 ? volume = 0 : volume=volume; 
// 		volumeFill.style.width = `${volume*100}%`;
// 		video.volume = volume;
// 		if (volume > 0.7) {
// 			volumeBtn.classList.add('loud');
// 		} else if (volume < 0.7 && volume > 0) {
// 			volumeBtn.classList.remove('loud');
// 		} else if (volume == 0) {
// 			volumeBtn.classList.add('muted');
// 		}
// 		lastVolume = volume;
// }

// function neatTime(time) {
//   var minutes = Math.floor((time % 3600)/60);
//   var seconds = Math.floor(time % 60);
// 	seconds = seconds>9?seconds:`0${seconds}`;
// 	return `${minutes}:${seconds}`;
// }
function updateProgress(e) {
	// progressFill.style.width = `${video.currentTime/video.duration*100}%`;
	// textCurrent.innerHTML = `${neatTime(video.currentTime)} / ${neatTime(video.duration)}`;
  // console.log('asd')
  slide.slider("value", video.currentTime)
  // video.currentTime
}
// function setProgress(e) {
//   console.log('asd2')
// 	const newTime = e.offsetX/progressSlider.offsetWidth;
// 	progressFill.style.width = `${newTime*100}%`;
// 	video.currentTime = newTime*video.duration;
// }
function launchIntoFullscreen(element) {
  if(element.requestFullscreen) {
    element.requestFullscreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullscreen) {
    element.webkitRequestFullscreen();
  } else if(element.msRequestFullscreen) {
    element.msRequestFullscreen();
  }
}
function exitFullscreen() {
  if(document.exitFullscreen) {
    document.exitFullscreen();
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if(document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  }
}
var fullscreen = false;
function toggleFullscreen() {
	fullscreen? exitFullscreen() : launchIntoFullscreen(player)
	fullscreen = !fullscreen;
  fullscreenBtn.classList.toggle('fullscreen-apply');
  makeLandscape()
}

function makeLandscape() {
  if (screen.orientation && screen.orientation.lock) {
    screen.orientation.lock('landscape');
  }
}

// function setSpeed(e) {
// 	console.log(parseFloat(this.dataset.speed));
// 	video.playbackRate = this.dataset.speed;
// 	speedBtns.forEach(speedBtn =>	speedBtn.classList.remove('active'));
// 	this.classList.add('active');
// }

function handleKeypress(e) {
	switch (e.key) {
		case " ":
			togglePlay();
		case "ArrowRight":
			video.currentTime += 10;
		case "ArrowLeft":
			video.currentTime -= 10;
		default:
			return;
	}
}

//EVENT LISTENERS
playBtn.addEventListener('click', togglePlay);
video.addEventListener('click', togglePlay);
// video.addEventListener('play', togglePlayBtn);
// video.addEventListener('pause', togglePlayBtn);
// video.addEventListener('ended', togglePlayBtn);
video.addEventListener('timeupdate', updateProgress);
video.addEventListener('canplay', updateProgress);
// // volumeBtn.addEventListener('click', toggleMute);
// window.addEventListener('mousedown', () => isMouseDown = true)
// window.addEventListener('mouseup', () => isMouseDown = false)
// volumeSlider.addEventListener('mouseover', changeVolume);
// volumeSlider.addEventListener('click', changeVolume);
// progressSlider.addEventListener('click', setProgress);
fullscreenBtn.addEventListener('click', toggleFullscreen);
// speedBtns.forEach(speedBtn => {
// 	speedBtn.addEventListener('click', setSpeed);
// })
window.addEventListener('keydown', handleKeypress);






$(document).on('change','#switch',function(){
  var playing = $('#video').hasClass('hidden')?'audio':'video';
  // var vid = document.getElementById("audiovideoSwitch");
  var video = document.getElementById("video");
  var audio = document.getElementById("audio");
  console.log(video);
  console.log(video.currentTime);
  // var currentTime = vid.currentTime;
  switch (playing) {
      case "video":
        console.log('video',video)
        console.log('video.volume',video.volume)
        if (video.paused) {
          video.setAttribute("class", "hidden");
          audio.currentTime = video.currentTime;
          audio.playbackRate = video.playbackRate;
          audio.volume = video.volume;
          audio.setAttribute("class", "");
        }else{
            video.pause();
            video.setAttribute("class", "hidden");
            // video.removeChild(source);
            // audio.appendChild(source);
            audio.currentTime = video.currentTime;
            audio.playbackRate = video.playbackRate;
            audio.volume = video.volume;
            audio.setAttribute("class", "");
            audio.play();
            // playing = "audio";
        }
        break;
      case "audio":
        console.log('audio',audio)
        console.log('audio.volume',audio.volume)
        if (audio.paused) {
          audio.setAttribute("class", "hidden");
          video.currentTime = audio.currentTime;
          video.playbackRate = audio.playbackRate;
          video.volume = audio.volume;
          video.setAttribute("class", "");
        }else{
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

})